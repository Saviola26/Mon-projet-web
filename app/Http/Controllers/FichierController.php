<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Fichier;
use App\Models\Seminaire;

class FichierController extends Controller
{
    // Upload du fichier de présentation par le présentateur
    public function upload(Request $request, $seminaire_id)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:pdf,ppt,pptx|max:20480', // 20MB max
        ]);

        $seminaire = Seminaire::findOrFail($seminaire_id);

        // Vérification : seul le présentateur de ce séminaire peut uploader
        if (Auth::id() !== $seminaire->user_id) {
            abort(403, "Action non autorisée.");
        }

        $fichier = $request->file('fichier');
        $nomOriginal = $fichier->getClientOriginalName();

        // Générer un nom unique et le stocker
        $chemin = $fichier->storeAs('fichiers', uniqid() . '_' . $nomOriginal);

        // Enregistrement dans la BDD
        Fichier::create([
            'user_id' => Auth::id(),
            'seminaire_id' => $seminaire->id,
            'type' => 'presentation',
            'nom' => $nomOriginal,
            'chemin' => $chemin,
        ]);

        return back()->with('success', 'Fichier de présentation uploadé avec succès.');
    }

    // Téléchargement par les étudiants ou tout utilisateur connecté
    public function download($id)
    {
        $fichier = Fichier::findOrFail($id);

        if (!Storage::exists($fichier->chemin)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::download($fichier->chemin, $fichier->nom);
    }

    // Suppression par le secrétaire scientifique (admin)
    public function destroy($id)
    {
        $fichier = Fichier::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Seul le secrétaire scientifique peut supprimer les fichiers.');
        }

        // Supprimer le fichier du disque
        if (Storage::exists($fichier->chemin)) {
            Storage::delete($fichier->chemin);
        }

        $fichier->delete();

        return back()->with('success', 'Fichier supprimé avec succès.');
    }

    // Affiche le formulaire d’upload pour un séminaire donné
    public function showUploadForm($seminaire_id)
    {
        $seminaire = Seminaire::findOrFail($seminaire_id);

        // Seul le présentateur peut voir ce formulaire
        if (Auth::id() !== $seminaire->user_id) {
            abort(403);
        }

        return view('fichiers.upload', compact('seminaire'));
    }

    // Liste des fichiers pour les étudiants
    public function index()
    {
        $fichiers = Fichier::with('seminaire')->where('type', 'presentation')->get();

        return view('fichiers.index', compact('fichiers'));
    }

}

