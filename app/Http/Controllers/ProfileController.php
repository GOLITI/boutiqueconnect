<?php

namespace App\Http\Controllers;

use App\Models\User; // Assurez-vous que cette ligne est présente
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash; // Pour hacher le mot de passe si modifié
use Illuminate\Support\Facades\Storage; // Pour la gestion du stockage des fichiers

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user(); // Récupère l'utilisateur connecté
        return view('profile.show', compact('user')); // Passe l'utilisateur à la vue
    }

    /**
     * Affiche le formulaire d'édition du profil de l'utilisateur connecté.
     */
    public function edit()
    {
        // Récupère l'utilisateur actuellement authentifié
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Met à jour les informations du profil de l'utilisateur.
     */
    /**
     * Met à jour les informations du profil de l'utilisateur.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Valider les données du formulaire
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // Validation pour l'image (2MB max)
            'current_password' => ['nullable', 'string', 'min:8'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Vérifier le mot de passe actuel si un nouveau mot de passe est fourni
        if ($request->filled('password') && !Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        // Gérer le téléchargement de la photo de profil
        if ($request->hasFile('profile_photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Stocker la nouvelle photo dans le dossier 'public' de storage
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        // Mettre à jour les informations de l'utilisateur
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;

        // Mettre à jour le mot de passe si un nouveau est fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Votre profil a été mis à jour avec succès !');
    }

    

}
