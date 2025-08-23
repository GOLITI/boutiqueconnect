<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Credit extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Colonnes de la table `credits`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',       // Clé étrangère vers l'utilisateur qui a accordé le crédit
        'customer_id',   // Clé étrangère vers le client qui a le crédit
        'sale_id',       // Clé étrangère vers la vente associée (peut être null si le crédit est autonome)
        'amount',        // Montant initial du crédit (montant dû)
        'amount_paid',   // Montant déjà remboursé par le client
        'due_date',      // Date d'échéance du crédit (facultatif)
        'description',   // Description ou raison du crédit (facultatif)
        'status',        // Statut du crédit (ex: 'outstanding', 'partially_paid', 'paid', 'overdue')
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
    ];

    /*
     * Relations avec d'autres modèles :
     */

    // Un crédit appartient à un utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Un crédit appartient à un client
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Un crédit peut être lié à une vente
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    // Un crédit peut avoir plusieurs paiements (remboursements partiels ou complets)
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
