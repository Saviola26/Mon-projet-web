<?php

namespace App\Http\Controllers;

use App\Models\Seminaire;
use App\Models\User;
use App\Models\Fichier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\SeminaireValideMail;
use App\Mail\SeminairePublieMail;
use App\Mail\PlanningModifieMail;

class SeminaireController extends Controller
{
    // Affiche la liste des séminaires
    public function index()
    {
        $seminaires = Seminaire::with('presentateur')->get();
        return view('seminaires.index', compact('seminaires'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        if (!in_array(auth()->user()->role, ['presentateur', 'admin'])) {
            abort(403, 'Accès interdit. Seuls les présentateurs ou les administrateurs peuvent créer un séminaire.');
        }
        return view('seminaires.create');
    }

    // Enregistre un nouveau séminaire
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'theme' => 'required|string',
            'date_proposee' => 'required|date',
            'nombre_max_participants' => 'required|integer|min:1',
            'presentateur_nom_saisi' => 'nullable|string|max:255',
        ]);

        $presentateurId = Auth::id();
        if (Auth::check() && Auth::user()->role === 'admin' && $request->filled('presentateur_nom_saisi')) {
            $nomPresentateurSoumis = $request->input('presentateur_nom_saisi');
            $utilisateurPresentateur = User::where('name', $nomPresentateurSoumis)->first();

            if ($utilisateurPresentateur) {
                $presentateurId = $utilisateurPresentateur->id;
            } else {
                return back()
                    ->withErrors(['presentateur_nom_saisi' => 'Aucun utilisateur trouvé avec le nom "' . htmlspecialchars($nomPresentateurSoumis) . '". Laissez vide pour vous désigner comme présentateur.'])
                    ->withInput();
            }
        }

        try {
            Seminaire::create([
                'theme' => $validatedData['theme'],
                'date_proposee' => $validatedData['date_proposee'],
                'nombre_max_participants' => $validatedData['nombre_max_participants'],
                'user_id' => $presentateurId,
                'statut' => 'en_attente',
            ]);

            return redirect()->route('seminaires.index')->with('success', 'Demande de séminaire envoyée.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la création du séminaire.')->withInput();
        }
    }

    // Affiche les détails d’un séminaire
    public function show(Seminaire $seminaire)
    {
        return view('seminaires.show', compact('seminaire'));
    }

    // Formulaire d’édition (si nécessaire)
    public function edit(Seminaire $seminaire)
    {
        return view('seminaires.edit', compact('seminaire'));
    }

    // Met à jour un séminaire
    public function update(Request $request, Seminaire $seminaire)
    {
        $request->validate([
            'theme' => 'required|string',
            'date_proposee' => 'nullable|date',
            'resume' => 'nullable|string|max:1000',
            'nombre_max_participants' => 'required|integer|min:1'
        ]);

        $seminaire->update($request->all());

        return redirect()->route('seminaires.index')->with('success', 'Séminaire mis à jour.');
    }

    // Supprime un séminaire
    public function destroy(Seminaire $seminaire)
    {
        if (Auth::id() !== $seminaire->user_id && Auth::user()->role !== 'admin') {
            return redirect()->route('seminaires.index')->with('error', 'Vous n\'êtes pas autorisé à supprimer ce séminaire.');
        }

        $seminaire->delete();

        return redirect()->route('seminaires.index')->with('success', 'Séminaire supprimé.');
    }

    // Validation du séminaire (action du secrétaire)
    public function valider(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('seminaires.index')->with('error', 'Vous n\'êtes pas autorisé à valider ce séminaire.');
        }

        $seminaire = Seminaire::findOrFail($id);
        $request->validate([
            'date_validee' => 'required|date|after_or_equal:today',  
        ]);

        $seminaire->date_validee = $request->date_validee; // Exemple : 2 semaines après
        $seminaire->statut = 'valide';
        $seminaire->save();

        // TODO : Envoi d’un email ici si souhaité
        if ($seminaire->presentateur && $seminaire->presentateur->email) {
            Mail::to($seminaire->presentateur->email)->send(new SeminaireValideMail($seminaire));
        } else {
            \Log::warning("Impossible d'envoyer l'email de validation pour le séminaire ID: {$seminaire->id}. Présentateur ou email manquant.");
        }

        return back()->with('success', 'Séminaire validé.');
    }

    // Le présentateur envoie le résumé
    public function envoyerResume(Request $request, $id)
    {
        $request->validate([
            'resume' => 'required|string',
        ]);

        $seminaire = Seminaire::findOrFail($id);
        if ($seminaire->user_id !== Auth::id()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à soumettre un résumé pour ce séminaire.');
        }
        if (empty($seminaire->date_validee)) {
            return back()->with('error', 'Le séminaire n\'a pas encore été validé.');
        }
        $dateValidee = Carbon::parse($seminaire->date_validee);
        $dateLimite = $dateValidee->subDays(10);
        if (now()->lt($dateLimite)) {
            return back()->with('error', 'Vous ne pouvez soumettre le résumé qu\'à partir de 10 jours avant la date de présentation.');
        }

        $seminaire->resume = $request->resume;
        $seminaire->save();

        return back()->with('success', 'Résumé envoyé.');
    }


    public function publier($id)
    {
        if (Auth::user()->role !== 'admin') { 
            return back()->with('error', 'Vous n\'êtes pas autorisé à publier ce séminaire.');
        }
        
        $seminaire = Seminaire::findOrFail($id);
        if (empty($seminaire->date_validee)) {
            return back()->with('error', 'Le séminaire doit être validé avant sa publication.');
        }
        $sevenDaysBefore = Carbon::parse($seminaire->date_validee)->subDays(7);
        $currentDate = Carbon::now();

        // TODO : logiques d’envoi d’emails aux étudiants
        if ($currentDate->isSameDay($sevenDaysBefore)) {
            // Récupérer tous les étudiants inscrits au séminaire
            $etudiants = $seminaire->participants()->where('role', 'etudiant')->get();
    
            // Envoyer un email à chaque étudiant
            foreach ($etudiants as $etudiant) {
                Mail::to($etudiant->email)->send(new SeminairePublieMail($seminaire));
            }
    
            // Retourner avec un message de succès
            return back()->with('success', 'Séminaire publié aux étudiants.');
        }

        return back()->with('error', 'L\'envoi des emails peut seulement être effectué 7 jours avant la date de validation.');
    }

    public function mesDemandes()
    {
        // Récupère tous les séminaires soumis par l'utilisateur connecté
        $seminaires = Seminaire::where('user_id', Auth::id())->latest()->get();

        // Retourne la vue avec les séminaires de l'utilisateur
        return view('seminaires.mes_demandes', compact('seminaires'));
    }

    public function participer(Seminaire $seminaire)
    {
        $user = auth()->user();
        
        if ($seminaire->statut !== 'valide') {
            return back()->with('error', 'Inscription impossible...');
        }
        
        if (!$seminaire->peutAccepterPlusDeParticipants()) {
            return back()->with('error', 'Ce séminaire est complet !');
        }

        if ($user->seminairesParticipations()->where('seminaire_id', $seminaire->id)->exists()) {
            return back()->with('warning', 'Vous êtes déjà inscrit');
        }

        $user->seminairesParticipations()->attach($seminaire->id, [
            'date_inscription' => now(),
        ]);

        $seminaire->incrementerParticipants();

        return back()->with('success', 'Inscription réussie !');
    }

    public function annulerParticipation(Seminaire $seminaire)
    {
        auth()->user()->seminairesParticipations()->detach($seminaire->id);
        $seminaire->decrementerParticipants();
        
        return back()->with('success', 'Désinscription effectuée');
    }

    public function mesInscriptions()
    {
        $user = auth()->user();
        
        $seminairesInscrits = $user->seminairesParticipations()
                                   ->where('statut', 'valide')
                                   ->where('date_validee', '>=', now())
                                   ->orderBy('date_validee')
                                   ->with('presentateur') 
                                   ->get();

        return view('seminaires.mes_inscriptions', compact('seminairesInscrits'));
    }

    public function historique()
    {
        $seminaires = Seminaire::with(['presentateur', 'participants', 'fichiers'])
            ->where('statut', 'termine')
            ->latest('date_validee')
            ->get();

        return view('seminaires.historique', compact('seminaires'));
    }

    public function marquerCommeTermine(Seminaire $seminaire)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $seminaire->statut = 'termine';
        $seminaire->save();

        return back()->with('success', 'Le statut du séminaire a bien été mis à jour à "Terminé".');
    }

    public function showUploadPresentationForm(Seminaire $seminaire)
    {
        // 1. Autorisation : Seul le présentateur du séminaire ou un admin peut accéder
        if (Auth::id() !== $seminaire->user_id && (Auth::check() && Auth::user()->role !== 'admin')) {
            abort(403, 'Accès non autorisé.');
        }

        // 2. Condition de date : La date validée du séminaire doit être passée
        if (!Carbon::parse($seminaire->date_validee)->isPast()) {
            return back()->with('error', 'Vous ne pouvez téléverser la présentation qu\'après la date du séminaire.');
        }

        return view('fichiers.upload', compact('seminaire'));
    }

    public function handleUploadPresentation(Request $request, Seminaire $seminaire)
    {
       
        if (Auth::id() !== $seminaire->user_id && (Auth::check() && Auth::user()->role !== 'admin')) {
            abort(403, 'Accès non autorisé.');
        }

        if (!Carbon::parse($seminaire->date_validee)->isPast()) {
            return back()->with('error', 'Vous ne pouvez téléverser la présentation qu\'après la date du séminaire.');
        }

        $request->validate([
            'fichier_presentation' => 'required|file|mimes:pdf,ppt,pptx|max:20480', // Max 20MB, ajuste selon tes besoins
        ], [
            'fichier_presentation.required' => 'Veuillez sélectionner un fichier.',
            'fichier_presentation.mimes' => 'Le fichier doit être un PDF, PPT ou PPTX.',
            'fichier_presentation.max' => 'La taille du fichier ne doit pas dépasser 20 Mo.',
        ]);

        if ($request->hasFile('fichier_presentation')) {
            $file = $request->file('fichier_presentation');

            $path = $file->storeAs(
                'seminaire_presentations/' . $seminaire->id, 
                $file->getClientOriginalName(), 
                'public' 
            );

            Fichier::create([
                'seminaire_id' => $seminaire->id,
                'user_id' => Auth::id(), 
                'type' => 'presentation', 
                'nom' => $file->getClientOriginalName(),
                'chemin' => $path,
                'type_mime' => $file->getMimeType(),
                'taille' => $file->getSize(),
            ]);

            $seminaire->statut = 'termine';
            $seminaire->save();

            return redirect()->route('seminaires.show', $seminaire)->with('success', 'Fichier de présentation téléversé avec succès et séminaire marqué comme terminé !');
        }

        return back()->with('error', 'Une erreur est survenue lors du téléversement du fichier.');
    }
}

