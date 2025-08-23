<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Gère la soumission du formulaire de connexion.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tente de connecter l'utilisateur
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate(); // Régénère l'ID de session pour la sécurité

            // Redirige vers le tableau de bord après la connexion réussie
            return redirect()->intended(route('dashboard')); // Redirige l'utilisateur vers le dashboard
        }

        // Si la connexion échoue, renvoie une erreur de validation
        throw ValidationException::withMessages([
            'email' => __('auth.failed'), // 'auth.failed' est un message d'erreur par défaut de Laravel pour les identifiants invalides
        ]);
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Déconnecte l'utilisateur

        $request->session()->invalidate(); // Invalide la session actuelle
        $request->session()->regenerateToken(); // Régénère le jeton CSRF

        // Redirige vers la page d'accueil après la déconnexion
        return redirect()->route('home'); 
    }
}
