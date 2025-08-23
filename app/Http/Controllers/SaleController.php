<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer; // N'oubliez pas d'importer le modèle Customer
use App\Models\Credit; // N'oubliez pas d'importer le modèle Credit
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Affiche une liste des ventes. (Exemple)
     */
    public function index()
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $sales = $user->sales()->with('customer')->orderByDesc('sale_date')->paginate(10);
        return view('sales.index', compact('sales'));
    }
    

    /**
     * Affiche les détails d'une vente spécifique.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\View\View
     */
    public function show(Sale $sale)
    {
        if (Auth::id() !== $sale->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        $sale->load('saleItems.product', 'customer'); // Charger la relation customer
        return view('sales.show', compact('sale'));
    }


    /**
     * Affiche le formulaire pour créer une nouvelle vente.
     */
    public function create()
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $products = $user->products()->select('id', 'name', 'sale_price', 'stock_quantity')->get();
        // Récupérer les clients existants pour la sélection
        $customers = $user->customers()->select('id', 'name', 'phone_number')->get();

        return view('sales.create', compact('products', 'customers'));
    }

    /**
     * Stocke une nouvelle vente dans la base de données.
     */
    public function store(Request $request)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();

        // Règles de validation
        $rules = [
            'customer_type' => ['required', 'in:existing,new_temp'],
            'customer_id' => ['nullable', 'exists:customers,id'], // Validé conditionnellement
            'client_name_new' => ['nullable', 'string', 'max:255'], // Validé conditionnellement
            'client_phone_new' => ['nullable', 'string', 'max:20'], // Validé conditionnellement
            'total_amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'in:Cash,Mobile Money,Credit'],
            'change_amount' => ['nullable', 'numeric'],
            'cart_items' => ['required', 'array', 'min:1'], // Le panier ne doit pas être vide
            'cart_items.*.product_id' => ['required', 'exists:products,id'],
            'cart_items.*.quantity' => ['required', 'integer', 'min:1'],
            'cart_items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];

        // Messages d'erreur personnalisés (facultatif mais recommandé pour plus de clarté)
        $messages = [
            'customer_id.exists' => 'Le client sélectionné n\'existe pas.',
            'client_name_new.required_if' => 'Le nom du nouveau client est requis.',
            'client_phone_new.required_if' => 'Le numéro de téléphone du nouveau client est requis.',
            'total_amount.required' => 'Le montant total de la vente est requis.',
            'total_amount.min' => 'Le montant total doit être supérieur ou égal à 0.',
            'paid_amount.required' => 'Le montant payé est requis.',
            'paid_amount.min' => 'Le montant payé doit être supérieur ou égal à 0.',
            'payment_method.in' => 'La méthode de paiement sélectionnée est invalide.',
            'cart_items.required' => 'Le panier ne peut pas être vide. Veuillez ajouter des produits.',
            'cart_items.min' => 'Le panier doit contenir au moins un produit.',
            'cart_items.*.product_id.exists' => 'Un des produits du panier n\'existe pas.',
            'cart_items.*.quantity.min' => 'La quantité d\'un produit doit être au moins 1.',
        ];

        // Validation conditionnelle des champs client
        if ($request->input('customer_type') === 'existing') {
            $rules['customer_id'] = ['required', 'exists:customers,id'];
        } elseif ($request->input('customer_type') === 'new_temp') {
            $rules['client_name_new'] = ['required', 'string', 'max:255'];
            $rules['client_phone_new'] = ['required', 'string', 'max:20'];
        }

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $customerId = null;
            if ($request->input('customer_type') === 'existing') {
                $customerId = $request->input('customer_id');
            } elseif ($request->input('customer_type') === 'new_temp') {
                // Créer un nouveau client temporaire
                $newCustomer = Customer::create([
                    'user_id' => $user->id,
                    'name' => $request->input('client_name_new'),
                    'phone_number' => $request->input('client_phone_new'),
                    'address' => null, // L'adresse n'est pas requise pour un client temporaire
                    'email' => null,    // L'email n'est pas requis pour un client temporaire
                    'total_credit_debt' => 0, // Initialisé à 0
                ]);
                $customerId = $newCustomer->id;
            }

            // Calculer le statut de la vente
            $totalAmount = $request->input('total_amount');
            $paidAmount = $request->input('paid_amount');
            $paymentMethod = $request->input('payment_method');
            $status = 'completed'; // Par défaut

            if ($paymentMethod === 'Credit') {
                $status = 'pending_credit';
            } elseif ($paidAmount < $totalAmount && $paymentMethod !== 'Credit') {
                // Cas où le montant payé est insuffisant pour un paiement non-crédit
                // Cela ne devrait pas arriver avec une bonne gestion JS ou pourrait être 'partiel'
                $status = 'partial'; // Un nouveau statut si vous gérez des paiements partiels sans crédit
            }

            // Créer la vente principale
            $sale = Sale::create([
                'user_id' => $user->id,
                'customer_id' => $customerId, // Peut être null pour les clients de passage non enregistrés
                'sale_date' => Carbon::now(),
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $request->input('change_amount'),
                'payment_method' => $paymentMethod,
                'status' => $status,
            ]);

            // Ajouter les articles de vente et mettre à jour le stock
            foreach ($request->input('cart_items') as $itemData) {
                $product = Product::find($itemData['product_id']);

                if (!$product || $product->stock_quantity < $itemData['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'Quantité insuffisante pour le produit : ' . ($product ? $product->name : 'Inconnu'));
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $itemData['quantity'] * $itemData['unit_price'],
                ]);

                // Diminuer la quantité en stock du produit
                $product->decrement('stock_quantity', $itemData['quantity']);
            }

            // Gérer le crédit si la méthode de paiement est 'Crédit'
            if ($paymentMethod === 'Credit') {
                $amountDue = $totalAmount - $paidAmount;
                if ($amountDue > 0) {
                    Credit::create([
                        'user_id' => $user->id,
                        'customer_id' => $customerId,
                        'sale_id' => $sale->id,
                        'amount' => $amountDue,
                        'amount_paid' => 0, // Initialement 0 pour le crédit
                        'due_date' => Carbon::now()->addMonth(), // Exemple: échéance dans 1 mois
                        'description' => 'Crédit pour la vente #' . $sale->id,
                        'status' => 'outstanding', // En attente
                    ]);

                    // Mettre à jour la dette totale du client
                    if ($customerId) {
                        $customer = Customer::find($customerId);
                        if ($customer) {
                            $customer->increment('total_credit_debt', $amountDue);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            // Pour le débogage, vous pouvez loguer l'erreur ou la retourner
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'enregistrement de la vente : ' . $e->getMessage());
        }
    }
}
