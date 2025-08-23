<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Colonnes de la table `customers`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',         // Clé étrangère vers l'utilisateur propriétaire du client
        'name',            // Nom complet du client
        'phone_number',    // Numéro de téléphone du client (peut être unique)
        'address',         // Adresse du client (facultatif)
        'email',           // Email du client (facultatif, peut être unique)
        'total_credit_debt', // Montant total des crédits/dettes du client (peut être calculé ou mis à jour)
    ];

    /*
     * Relations avec d'autres modèles :
     */

    // Un client appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un client peut avoir plusieurs ventes
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Un client peut avoir plusieurs crédits/dettes
    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * Accesseur pour obtenir le nom complet du client avec son numéro de téléphone.
     * Cela crée une propriété virtuelle $customer->display_name.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->name && $this->phone_number) {
            return "{$this->name} ({$this->phone_number})";
        } elseif ($this->name) {
            return $this->name;
        } elseif ($this->phone_number) {
            return $this->phone_number;
        }
        return 'Client Inconnu';
    }
}
