<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Customer; // Nécessaire pour lier un crédit à un client
use App\Models\Sale;     // Nécessaire pour lier un crédit à une vente
use App\Models\Payment;  // Nécessaire pour enregistrer les paiements
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreditController extends Controller
{
    /**
     * Affiche la liste paginée des crédits du commerçant connecté avec des options de filtrage.
     */
    public function index(Request $request)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $query = $user->credits()->with('customer', 'sale'); // Charger les relations

        // Filtrage par client
        if ($request->has('customer_id') && !empty($request->customer_id)) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filtrage par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filtrage par date d'échéance (simple: avant ou après une date)
        if ($request->has('due_date_before') && !empty($request->due_date_before)) {
            $query->where('due_date', '<=', $request->due_date_before);
        }
        if ($request->has('due_date_after') && !empty($request->due_date_after)) {
            $query->where('due_date', '>=', $request->due_date_after);
        }

        /** @var App\Models\User $user */
        $user = Auth::user();
        $credits = $query->orderBy('due_date')->paginate(10);
        $customers = $user->customers()->select('id', 'name')->get(); // Pour le filtre client

        return view('credits.index', compact('credits', 'customers'));
    }

    /**
     * Affiche le formulaire pour créer un nouveau crédit.
     */
    public function create(Request $request)
    {
       /** @var App\Models\User $user */
        $user = Auth::user();
        $customers = $user->customers()->select('id', 'name', 'phone_number')->get();
        $sales = $user->sales()->select('id', 'total_amount', 'sale_date')->latest()->take(50)->get(); // Limiter aux 50 dernières ventes

        // Permettre de pré-sélectionner un client si un customer_id est passé dans l'URL
        $selectedCustomer = null;
        if ($request->has('customer_id')) {
            $selectedCustomer = $user->customers()->find($request->customer_id);
        }

        return view('credits.create', compact('customers', 'sales', 'selectedCustomer'));
    }

    /**
     * Stocke un nouveau crédit dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'sale_id' => ['nullable', 'exists:sales,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            /** @var App\Models\User $user */
            $user = Auth::user();
            $credit = $user->credits()->create([
                'customer_id' => $request->customer_id,
                'sale_id' => $request->sale_id,
                'amount' => $request->amount,
                'amount_paid' => 0, // Nouveau crédit, montant payé est 0
                'due_date' => $request->due_date,
                'description' => $request->description,
                'status' => 'outstanding', // Par défaut en attente
            ]);

            // Mettre à jour la dette totale du client
            $customer = $credit->customer;
            $customer->increment('total_credit_debt', $request->amount);
        });

        return redirect()->route('credits.index')->with('success', 'Crédit enregistré avec succès !');
    }

    /**
     * Affiche les détails d'un crédit spécifique et son historique de paiements.
     */
    public function show(Credit $credit)
    {
        // Assurez-vous que l'utilisateur connecté est bien le propriétaire du crédit
        if (Auth::id() !== $credit->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        $credit->load('customer', 'sale', 'payments'); // Charger toutes les relations nécessaires

        return view('credits.show', compact('credit'));
    }

    /**
     * Affiche le formulaire pour modifier un crédit existant.
     */
    public function edit(Credit $credit)
    {
        if (Auth::id() !== $credit->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        /** @var App\Models\User $user */
        $user = Auth::user();

        $customers = $user->customers()->select('id', 'name', 'phone_number')->get();
        $sales = $user->sales()->select('id', 'total_amount', 'sale_date')->latest()->take(50)->get();

        return view('credits.edit', compact('credit', 'customers', 'sales'));
    }

    /**
     * Met à jour un crédit existant dans la base de données.
     */
    public function update(Request $request, Credit $credit)
    {
        if (Auth::id() !== $credit->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'sale_id' => ['nullable', 'exists:sales,id'],
            'amount' => ['required', 'numeric', 'min:0', 'gte:' . $credit->amount_paid], // Le nouveau montant doit être >= montant payé
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', 'in:outstanding,partially_paid,paid,overdue'],
        ]);

        DB::transaction(function () use ($request, $credit) {
            // Mettre à jour la dette totale du client si le montant du crédit change
            if ($request->amount != $credit->amount) {
                $customer = $credit->customer;
                $diff = $request->amount - $credit->amount;
                $customer->increment('total_credit_debt', $diff);
            }

            $credit->update([
                'customer_id' => $request->customer_id,
                'sale_id' => $request->sale_id,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'description' => $request->description,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('credits.index')->with('success', 'Crédit mis à jour avec succès !');
    }

    /**
     * Supprime un crédit de la base de données.
     * ATTENTION: Cela va affecter le solde de la dette du client.
     */
    public function destroy(Credit $credit)
    {
        if (Auth::id() !== $credit->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        DB::transaction(function () use ($credit) {
            // Déduire le montant restant du crédit de la dette totale du client
            $customer = $credit->customer;
            $remainingAmount = $credit->amount - $credit->amount_paid;
            $customer->decrement('total_credit_debt', $remainingAmount);

            $credit->delete(); // Supprime le crédit et ses paiements associés (si onDelete('cascade') est défini sur `payments` migration)
        });

        return redirect()->route('credits.index')->with('success', 'Crédit supprimé avec succès !');
    }

    /**
     * Affiche le formulaire pour enregistrer un paiement pour un crédit.
     */
    public function showRecordPayment(Credit $credit)
    {
        if (Auth::id() !== $credit->user_id) {
            abort(403, 'Accès non autorisé.');
        }
        return view('credits.record-payment', compact('credit'));
    }

    /**
     * Enregistre un paiement pour un crédit et met à jour les soldes.
     */
    public function recordPayment(Request $request, Credit $credit)
    {
        if (Auth::id() !== $credit->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'amount_paid' => ['required', 'numeric', 'min:0.01', 'max:' . ($credit->amount - $credit->amount_paid)], // Ne peut pas payer plus que le reste dû
            'payment_method' => ['required', 'string', 'in:Cash,Mobile Money,Bank Transfer'], // Ex: types de paiements
        ]);

        DB::transaction(function () use ($request, $credit) {
            // Créer un nouvel enregistrement de paiement
            /** @var App\Models\User $user */
            $user = Auth::user();
            $payment = $user->payments()->create([
                'credit_id' => $credit->id,
                'amount_paid' => $request->amount_paid,
                'payment_date' => Carbon::now(),
                'payment_method' => $request->payment_method,
            ]);

            // Mettre à jour le montant payé sur le crédit
            $credit->increment('amount_paid', $request->amount_paid);

            // Mettre à jour le statut du crédit
            if ($credit->amount_paid >= $credit->amount) {
                $credit->update(['status' => 'paid']);
            } elseif ($credit->amount_paid > 0) {
                $credit->update(['status' => 'partially_paid']);
            }

            // Mettre à jour la dette totale du client (déduire le montant payé)
            $customer = $credit->customer;
            $customer->decrement('total_credit_debt', $request->amount_paid);
        });

        return redirect()->route('credits.show', $credit->id)->with('success', 'Paiement enregistré avec succès !');
    }
}
