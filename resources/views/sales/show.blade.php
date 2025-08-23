@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Détails de la Vente #{{ $sale->id }}</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('sales.index') }}">Ventes</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('sales.show', $sale->id) }}">Détails</a></li>
            </ul>
        </div>
        <a href="{{ route('sales.index') }}" class="btn-download" style="background-color: var(--dark-grey);">
            <i class='bx bx-arrow-back' ></i>
            <span class="text">Retour aux Ventes</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Informations de la Vente</h3>
            </div>

            <div style="padding: 20px;">
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>ID Vente :</strong> #{{ $sale->id }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Date de Vente :</strong> {{ $sale->sale_date->format('d/m/Y H:i') }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Client :</strong> {{ $sale->customer ? $sale->customer->display_name : 'N/A' }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Montant Total :</strong> <span style="color: var(--blue); font-weight: 600;">{{ number_format($sale->total_amount, 0, ',', '.') }} XOF</span>
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Montant Payé :</strong> {{ number_format($sale->paid_amount, 0, ',', '.') }} XOF
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Monnaie Rendue :</strong> {{ number_format($sale->change_amount, 0, ',', '.') }} XOF
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Mode de Paiement :</strong> {{ $sale->payment_method }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Statut :</strong>
                    <span class="status {{ $sale->status === 'completed' ? 'completed' : ($sale->status === 'pending_credit' ? 'process' : 'pending') }}">
                        {{ $sale->status === 'completed' ? 'Terminée' : ($sale->status === 'pending_credit' ? 'Crédit' : 'Annulée') }}
                    </span>
                </p>
            </div>

            <div class="head" style="margin-top: 30px;">
                <h3>Articles Vendus</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sale->saleItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 0, ',', '.') }} XOF</td>
                            <td>{{ number_format($item->subtotal, 0, ',', '.') }} XOF</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px;">Aucun article pour cette vente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($sale->status !== 'cancelled')
                <div style="margin-top: 30px; text-align: center;">
                    <form action="{{ route('sales.cancel', $sale->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" style="background-color: var(--red); color: white; padding: 12px 25px; border-radius: 8px; border: none; font-size: 16px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;"
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette vente et remettre les produits en stock ? Cette action est irréversible.');">
                            <i class='bx bx-x-circle'></i> Annuler la Vente
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
