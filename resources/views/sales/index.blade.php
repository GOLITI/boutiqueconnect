@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Historique des Ventes</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('sales.index') }}">Ventes</a></li>
            </ul>
        </div>
        <a href="{{ route('sales.create') }}" class="btn-download" style="background-color: var(--blue);">
            <i class='bx bx-plus' ></i>
            <span class="text">Nouvelle Vente</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Ventes Récentes</h3>
                <form action="{{ route('sales.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                    <input type="text" name="client_search" placeholder="Nom client..." value="{{ request('client_search') }}"
                           style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                    <select name="payment_method"
                            style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="">Mode de paiement</option>
                        <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Mobile Money" {{ request('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="Credit" {{ request('payment_method') == 'Credit' ? 'selected' : '' }}>Crédit</option>
                    </select>
                    <button type="submit" class="search-btn" style="background-color: var(--blue); color: white; padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer;">
                        <i class='bx bx-filter'></i> Filtrer
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn-download" style="background-color: var(--dark-grey); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none;">
                        <i class='bx bx-reset'></i> Réinitialiser
                    </a>
                </form>
            </div>

            @if (session('success'))
                <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>ID Vente</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Montant Total</th>
                        <th>Payé</th>
                        <th>Monnaie</th>
                        <th>Méthode</th>
                        <th>Statut</th>
                        <th>Actions</th> {{-- Assurez-vous que cette colonne est présente dans votre <thead> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr class="{{ $sale->status === 'pending_credit' ? 'pending-credit-row' : '' }} {{ $sale->status === 'cancelled' ? 'cancelled-sale-row' : '' }}">
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->customer ? $sale->customer->display_name : 'Client de passage' }}</td>
                            <td>{{ number_format($sale->total_amount, 0, ',', '.') }} XOF</td>
                            <td>{{ number_format($sale->paid_amount, 0, ',', '.') }} XOF</td>
                            <td>{{ number_format($sale->change_amount, 0, ',', '.') }} XOF</td>
                            <td>{{ $sale->payment_method }}</td>
                            <td>
                                <span class="status {{ $sale->status === 'completed' ? 'completed' : ($sale->status === 'pending_credit' ? 'process' : 'pending') }}">
                                    {{ $sale->status === 'completed' ? 'Terminée' : ($sale->status === 'pending_credit' ? 'Crédit' : 'Annulée') }}
                                </span>
                            </td>
                            <td> {{-- C'EST ICI QUE LES ACTIONS DOIVENT SE TROUVER --}}
                                <a href="{{ route('sales.show', $sale->id) }}" style="color: var(--blue); margin-right: 10px;" title="Voir détails"><i class='bx bx-info-circle'></i></a>
                                @if ($sale->status !== 'cancelled')
                                    <form action="{{ route('sales.cancel', $sale->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" style="background: none; border: none; color: var(--red); cursor: pointer; padding: 0;"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette vente et remettre les produits en stock ? Cette action est irréversible.');"
                                                        title="Annuler la vente">
                                                    <i class='bx bx-x-circle'></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px;">Aucune vente trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $sales->links() }}
            </div>
        </div>
    </div>

    <style>
        .pending-credit-row {
            background-color: var(--light-yellow); /* Jaune clair pour les ventes à crédit */
        }
        .cancelled-sale-row {
            background-color: var(--light-orange); /* Orange clair pour les ventes annulées */
            opacity: 0.7; /* Rendre un peu transparent */
        }
        .cancelled-sale-row td {
            text-decoration: line-through; /* Barrer le texte */
        }
        .status.process { /* Utilisé pour "Crédit" */
            background-color: var(--yellow);
        }
        .status.pending { /* Utilisé pour "Annulée" */
            background-color: var(--red);
        }
    </style>
@endsection
