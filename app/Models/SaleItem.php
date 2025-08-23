<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Colonnes de la table `sale_items`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',      // Clé étrangère vers la vente à laquelle cet article appartient
        'product_id',   // Clé étrangère vers le produit vendu
        'quantity',     // Quantité du produit vendue
        'unit_price',   // Prix unitaire du produit au moment de la vente (peut différer du prix actuel du produit)
        'subtotal',     // Sous-total pour cet article (quantity * unit_price)
    ];

    /*
     * Relations avec d'autres modèles :
     */

    // Un article de vente appartient à une vente
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Un article de vente appartient à un produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
