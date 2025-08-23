<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Gère la soumission du formulaire d'inscription.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Toujours hacher les mots de passe !
        ]);

        // Connecte l'utilisateur après l'inscription
        Auth::login($user);

        // Redirige vers le tableau de bord après l'inscription réussie
        return redirect()->route('dashboard');
    }
}
