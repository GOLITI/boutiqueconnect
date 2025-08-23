<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Pour les transactions si nécessaire

class CustomerController extends Controller
{
    /**
     * Affiche la liste paginée et recherchable des clients du commerçant connecté.
     */
    public function index(Request $request)
    {
        
        /** @var App\Models\User $user */
        $user = Auth::user();
        $query = $user->customers(); // Récupère les clients de l'utilisateur connecté

        // Filtrage par recherche (nom, téléphone, email, adresse)
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }

        $customers = $query->orderBy('name')->paginate(10); // Tri par nom, pagination

        return view('customers.index', compact('customers'));
    }

    /**
     * Affiche le formulaire pour créer un nouveau client.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Stocke un nouveau client dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:customers,email,NULL,id,user_id,' . Auth::id()], // Unique par utilisateur
        ]);
        
       
        /** @var App\Models\User $user */
        $user = Auth::user();
        $user->customers()->create($request->all());

        return redirect()->route('customers.index')->with('success', 'Client ajouté avec succès !');
    }

    /**
     * Affiche les détails d'un client spécifique (y compris son historique d'achats).
     */
    public function show(Customer $customer)
    {
        // Assurez-vous que l'utilisateur connecté est bien le propriétaire du client
        if (Auth::id() !== $customer->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        // Charge les ventes et les crédits liés au client
        $customer->load(['sales.saleItems.product', 'credits.payments']);

        return view('customers.show', compact('customer'));
    }

    /**
     * Affiche le formulaire pour modifier un client existant.
     */
    public function edit(Customer $customer)
    {
        // Assurez-vous que l'utilisateur connecté est bien le propriétaire du client
        if (Auth::id() !== $customer->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('customers.edit', compact('customer'));
    }

    /**
     * Met à jour un client existant dans la base de données.
     */
    public function update(Request $request, Customer $customer)
    {
        // Assurez-vous que l'utilisateur connecté est bien le propriétaire du client
        if (Auth::id() !== $customer->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:customers,email,' . $customer->id . ',id,user_id,' . Auth::id()], // Unique sauf pour ce client et cet utilisateur
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Client mis à jour avec succès !');
    }

    /**
     * Supprime un client de la base de données.
     * ATTENTION: La suppression d'un client dont les ventes ou crédits sont liés
     * dépend de la configuration onDelete dans les migrations.
     * Votre migration `sales` a `onDelete('set null')` pour `customer_id`, ce qui est bien.
     * Pour les crédits, assurez-vous que `customer_id` dans la table `credits` est `nullable`
     * et/ou a `onDelete('set null')` pour éviter des erreurs.
     */
    public function destroy(Customer $customer)
    {
        // Assurez-vous que l'utilisateur connecté est bien le propriétaire du client
        if (Auth::id() !== $customer->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        // Les relations `sales` et `credits` devraient être gérées par les contraintes
        // de clé étrangère dans la base de données (onDelete('set null')).
        // Aucune logique supplémentaire de suppression en cascade n'est nécessaire ici pour les ventes.
        // Pour les crédits, si customer_id est non-nullable et n'a pas onDelete('set null'),
        // la suppression échouera si le client a des crédits.

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Client supprimé avec succès !');
    }
}
