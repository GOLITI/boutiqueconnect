<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Colonnes de la table `users`.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',         // Nom du commerçant ou de la boutique
        'email',        // Email de connexion (doit être unique)
        'password',     // Mot de passe haché
        'phone_number', // Numéro de téléphone du commerçant
        'address',    // Adresse physique du commerçant/boutique (facultatif)
        'profile_photo' //photo de profil 
    ];

    /**
     * Les attributs qui doivent être masqués pour la sérialisation.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés à des types spécifiques.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
     * Relations avec d'autres modèles :
     */

    // Un utilisateur peut avoir plusieurs produits
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Un utilisateur peut avoir plusieurs ventes
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    // Un utilisateur peut avoir plusieurs clients 
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    // Un utilisateur peut gérer plusieurs crédits/dettes
    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    // Un utilisateur peut enregistrer plusieurs paiements (remboursements)
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    
     //Un utilisateur peut avoir plusieurs notifications.
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest(); // Les notifications les plus récentes en premier
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo
                    ? asset('storage/' . $this->profile_photo)
                    : asset('images/utilisateur.png'); // Image par défaut si aucune photo n'est uploadée
    }

    
}
