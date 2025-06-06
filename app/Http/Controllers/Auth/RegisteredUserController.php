<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'presentateur', 'etudiant'])], // ✅ validation du rôle
        ]);

        if ($request->role === 'admin') {
            $request->validate([
                'admin_name' => ['required', 'string'],
                'admin_code' => ['required', 'string'],
            ]);
    
            $adminNameSecret = env('ADMIN_NAME_SECRET');
            $adminCodeSecret = env('ADMIN_CODE_SECRET');
    
            if ($request->admin_name !== $adminNameSecret || $request->admin_code !== $adminCodeSecret) {
                return back()->withInput($request->except('password', 'password_confirmation', 'admin_code'))
                             ->withErrors(['admin_code' => 'Nom d\'administrateur ou code secret incorrect.']);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // ✅ enregistrement du rôle
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

}
