<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Colonnes de la table `sales`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',         // Clé étrangère vers l'utilisateur qui a effectué la vente
        'customer_id',     // Clé étrangère vers le client (peut être null si client de passage)
        'sale_date',       // Date et heure de la vente
        'total_amount',    // Montant total de la vente
        'paid_amount',     // Montant payé par le client
        'change_amount',   // Monnaie rendue au client (si paid_amount > total_amount)
        'payment_method',  // Méthode de paiement (ex: 'cash', 'mobile money', 'credit')
        'status',          // Statut de la vente (ex: 'completed', 'pending', 'partial_credit')
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sale_date' => 'datetime',
    ];

    /*
     * Relations avec d'autres modèles :
     */

    // Une vente appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une vente peut appartenir à un client (facultatif)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Une vente a plusieurs articles de vente (produits vendus dans cette transaction)
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Une vente peut être associée à un crédit (si une partie ou la totalité est à crédit)
    public function credit()
    {
        return $this->hasOne(Credit::class);
    }
}
