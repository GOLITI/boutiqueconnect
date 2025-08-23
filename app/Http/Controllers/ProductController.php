<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Importez la façade Log

class ProductController extends Controller
{
    /**
     * Affiche la liste des produits du commerçant connecté.
     * Permet la pagination et le filtrage par nom ou stock.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Product::where('user_id', $user->id);

        $search = null;

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($request->has('filter_stock') && $request->filter_stock == 'low') {
            $query->whereColumn('stock_quantity', '<=', 'min_stock_alert');
        }

        $products = $query->orderBy('name')->paginate(10);

        return view('products.index', compact('products', 'search'));
    }

    /**
     * Affiche le formulaire pour créer un nouveau produit.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Stocke un nouveau produit dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0', 'gte:purchase_price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_stock_alert' => ['required', 'integer', 'min:0', 'lte:stock_quantity'],
            'barcode' => ['nullable', 'string', 'max:255', 'unique:products,barcode,' . Auth::id() . ',user_id'],
            'image' => ['nullable', 'image', 'max:3100'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $imagePath = null;

        if ($request->hasFile('image')) {
            // Cette ligne stocke l'image dans storage/app/public/product-images
            $imagePath = $request->file('image')->store('product-images', 'public');
            Log::info('ProductController Store: Image uploaded to: ' . $imagePath); // DEBUG: Vérifiez ce chemin dans laravel.log
        } else {
            Log::info('ProductController Store: No image file provided.'); // DEBUG
        }

        $product = $user->products()->create([
            'name' => $request->name,
            'description' => $request->description,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'min_stock_alert' => $request->min_stock_alert,
            'barcode' => $request->barcode,
            'image' => $imagePath,
        ]);

        Log::info('ProductController Store: Product created with ID: ' . $product->id . ', Image Path in DB: ' . $product->image); // DEBUG

        // Vérifier si le stock est bas après la création
        if ($product->stock_quantity <= $product->min_stock_alert) {
            Log::info('ProductController Store: Triggering stock alert notification for new product: ' . $product->name); // DEBUG
            $this->createStockAlertNotification($user->id, $product);
        }

        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès !');
    }

    /**
     * Affiche le formulaire pour modifier un produit existant.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        if (Auth::id() !== $product->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('products.edit', compact('product'));
    }

    /**
     * Met à jour un produit existant dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        if (Auth::id() !== $product->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0', 'gte:purchase_price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_stock_alert' => ['required', 'integer', 'min:0', 'lte:stock_quantity'],
            'barcode' => ['nullable', 'string', 'max:255', 'unique:products,barcode,' . $product->id . ',id,user_id,' . Auth::id()],
            'image' => ['nullable', 'image', 'max:3100'],
        ]);

        $oldStockQuantity = $product->stock_quantity; // Stock avant la mise à jour
        $oldMinStockAlert = $product->min_stock_alert; // Seuil d'alerte avant la mise à jour

        // Gérer le téléchargement de la nouvelle image du produit
        if ($request->hasFile('image')) {
            Log::info('ProductController Update: New image file detected.'); // DEBUG
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
                Log::info('ProductController Update: Old image deleted: ' . $product->image); // DEBUG
            }
            // Cette ligne stocke l'image dans storage/app/public/product-images
            $imagePath = $request->file('image')->store('product-images', 'public');
            $product->image = $imagePath;
            Log::info('ProductController Update: New image uploaded to: ' . $imagePath); // DEBUG: Vérifiez ce chemin dans laravel.log
        } elseif ($request->boolean('remove_image')) {
            Log::info('ProductController Update: Remove image checkbox checked.'); // DEBUG
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
                Log::info('ProductController Update: Existing image deleted due to remove_image flag: ' . $product->image); // DEBUG
            }
            $product->image = null;
        } else {
            Log::info('ProductController Update: No new image, and remove_image not checked. Keeping existing image path: ' . $product->image); // DEBUG
        }

        // Mettre à jour les attributs du produit
        $product->name = $request->name;
        $product->description = $request->description;
        $product->purchase_price = $request->purchase_price;
        $product->sale_price = $request->sale_price;
        $product->stock_quantity = $request->stock_quantity;
        $product->min_stock_alert = $request->min_stock_alert;
        $product->barcode = $request->barcode;

        $product->save();
        Log::info('ProductController Update: Product saved. Current Image Path in DB: ' . $product->image); // DEBUG

        // Vérifier si le stock est bas après la mise à jour
        $isNowLowStock = ($product->stock_quantity <= $product->min_stock_alert);
        $wasPreviouslyAboveAlert = ($oldStockQuantity > $oldMinStockAlert);

        Log::info('ProductController Update: isNowLowStock=' . ($isNowLowStock ? 'true' : 'false') . ', wasPreviouslyAboveAlert=' . ($wasPreviouslyAboveAlert ? 'true' : 'false'));

        if ($isNowLowStock && ($wasPreviouslyAboveAlert || ($oldStockQuantity <= $oldMinStockAlert && $product->stock_quantity < $oldStockQuantity))) {
            Log::info('ProductController Update: Triggering stock alert notification due to update: ' . $product->name); // DEBUG
            $this->createStockAlertNotification(Auth::id(), $product);
        } else {
            Log::info('ProductController Update: Stock alert conditions not met for product: ' . $product->name); // DEBUG
        }

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Supprime un produit de la base de données.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        if (Auth::id() !== $product->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
            Log::info('ProductController Destroy: Image deleted: ' . $product->image); // DEBUG
        } else {
            Log::info('ProductController Destroy: No image or image not found for deletion: ' . $product->image); // DEBUG
        }

        $product->delete();
        Log::info('ProductController Destroy: Product deleted: ' . $product->name); // DEBUG

        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès !');
    }

    /**
     * Crée une notification d'alerte de stock bas.
     *
     * @param int $userId L'ID de l'utilisateur à notifier.
     * @param \App\Models\Product $product Le produit concerné par l'alerte.
     * @return void
     */
    private function createStockAlertNotification(int $userId, Product $product)
    {
        // Vérifier si une notification non lue existe déjà pour ce produit et ce type
        // Cela évite de créer plusieurs notifications pour le même problème de stock bas
        $existingNotification = Notification::where('user_id', $userId)
                                            ->where('type', 'stock_alert')
                                            ->where('is_read', false)
                                            ->whereJsonContains('data->product_id', $product->id)
                                            ->first();

        if ($existingNotification) {
            Log::info('createStockAlertNotification: Existing unread stock alert found for product ID ' . $product->id . '. Not creating a new one.'); // DEBUG
        } else {
            Log::info('createStockAlertNotification: No existing unread stock alert found for product ID ' . $product->id . '. Creating new notification.'); // DEBUG
            Notification::create([
                'user_id' => $userId,
                'type' => 'stock_alert',
                'message' => "Le stock du produit '{$product->name}' est bas ({$product->stock_quantity} restants). Seuil d'alerte: {$product->min_stock_alert}.",
                'data' => ['product_id' => $product->id, 'product_name' => $product->name],
            ]);
            Log::info('createStockAlertNotification: Notification created successfully for product ID ' . $product->id); // DEBUG
        }
    }
}
