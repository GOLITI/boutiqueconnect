<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Colonnes de la table `payments`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',       // Clé étrangère vers l'utilisateur qui a enregistré le paiement
        'credit_id',     // Clé étrangère vers le crédit auquel ce paiement est lié
        'amount_paid',   // Montant du remboursement
        'payment_date',  // Date et heure du paiement
        'payment_method',// Méthode de paiement du remboursement (ex: 'cash', 'mobile money')
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_date' => 'datetime',
    ];

    /*
     * Relations avec d'autres modèles :
     */

    // Un paiement appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un paiement appartient à un crédit
    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }
}
