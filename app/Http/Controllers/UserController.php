<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs.
     * Accessible typiquement par un admin.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d’un utilisateur.
     */
    public function create()
    {
        return view('users.create', ['isAdmin' => false]);
    }


    /**
     * Enregistre un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        $rules=[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,presentateur,etudiant',
        ];

        if ($request->role === 'admin') {
            $rules['admin_name'] = 'required|string';
            $rules['admin_code'] = 'required|string'; // Complété la règle pour admin_code
        }

        $request->validate($rules);

        if ($request->role === 'admin') {
            $adminNameSecret = env('ADMIN_NAME_SECRET');
            $adminCodeSecret = env('ADMIN_CODE_SECRET');

          
            if ($request->admin_name !== $adminNameSecret || $request->admin_code !== $adminCodeSecret) {
                // Retourne en arrière avec une erreur spécifique pour le champ admin_code
                return back()->withInput($request->except('password', 'password_confirmation', 'admin_code'))
                             ->withErrors(['admin_code' => 'Nom d\'administrateur ou code secret incorrect.']);
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche les détails d’un utilisateur.
     */
    public function show(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès interdit. Seuls les administrateurs peuvent voir les détails des utilisateurs.');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Formulaire d’édition d’un utilisateur.
     */
    public function edit(User $user)
    {
        // Seul l'utilisateur concerné ou un admin peut éditer
        if (auth()->id() !== $user->id && auth()->user()->role !== 'admin') {
            abort(403, 'Accès interdit. Vous n\'êtes pas autorisé à modifier ce compte.');
        }
        return view('users.edit', compact('user'));
    }

    /**
     * Met à jour un utilisateur existant.
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->id !== $user->id && Auth::user()->role !== 'admin') {
            // Si l'utilisateur n'est pas l'utilisateur lui-même et n'est pas un administrateur
            return redirect()->route('users.index')->with('error', 'Vous n\'êtes pas autorisé à modifier ce compte.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,presentateur,etudiant',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->id !== $user->id && Auth::user()->role !== 'admin') {
            // Si l'utilisateur n'est pas l'utilisateur lui-même et n'est pas un administrateur
            return redirect()->route('users.index')->with('error', 'Vous n\'êtes pas autorisé à modifier ce compte.');
        }
        if ($user->role === 'admin') { // Si l'utilisateur cible est un administrateur
            $smallestAdminId = User::where('role', 'admin')->min('id'); // Trouver l'ID de l'admin avec l'ID le plus petit

            if (auth()->id() !== $smallestAdminId) { // Si l'admin actuel n'est PAS l'admin avec l'ID le plus petit
                return redirect()->route('users.index')->with('error', 'Seul l\'administrateur principal (ID le plus petit) peut supprimer d\'autres administrateurs.');
            }
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}
