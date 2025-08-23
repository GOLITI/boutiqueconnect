<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Assurez-vous que cette ligne est présente

class Product extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Colonnes de la table `products`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',          // Clé étrangère vers l'utilisateur propriétaire du produit
        'name',             // Nom du produit
        'description',      // Description détaillée du produit (facultatif)
        'purchase_price',   // Prix d'achat du produit (coût)
        'sale_price',       // Prix de vente du produit
        'stock_quantity',   // Quantité en stock
        'min_stock_alert',  // Seuil pour l'alerte de stock bas
        'barcode',          // Code-barres du produit (unique, facultatif)
        'image',            // Chemin de l'image du produit (facultatif)
    ];

    /*
     * Relations avec d'autres modèles :
     */

    // Un produit appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un produit peut être inclus dans plusieurs articles de vente
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Accesseur pour obtenir l'URL complète de l'image du produit.
     * Cela crée une propriété virtuelle $product->image_url.
     * Si aucune image n'est définie ou si le fichier n'existe pas dans le stockage public,
     * retourne une image par défaut.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        // Vérifie si le chemin de l'image est défini ET si le fichier existe dans le stockage public
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        // Retourne une image par défaut si aucune image n'est définie ou si le fichier n'existe pas
        return asset('img/default-product.png');
    }
}
