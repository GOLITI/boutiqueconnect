@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Détails du Client</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('customers.index') }}">Clients</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('customers.show', $customer->id) }}">Détails</a></li>
            </ul>
        </div>
        <a href="{{ route('customers.index') }}" class="btn-download" style="background-color: var(--dark-grey);">
            <i class='bx bx-arrow-back' ></i>
            <span class="text">Retour aux Clients</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Informations du Client : {{ $customer->name }}</h3>
            </div>

            <div style="padding: 20px;">
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Nom :</strong> {{ $customer->name }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Téléphone :</strong> {{ $customer->phone_number ?: 'N/A' }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Email :</strong> {{ $customer->email ?: 'N/A' }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Adresse :</strong> {{ $customer->address ?: 'N/A' }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Crédit/Dette Actuel :</strong>
                    <span class="status {{ $customer->total_credit_debt > 0 ? 'process' : ($customer->total_credit_debt < 0 ? 'pending' : 'completed') }}">
                        {{ number_format($customer->total_credit_debt, 0, ',', '.') }} XOF
                    </span>
                </p>
            </div>

            <div class="head" style="margin-top: 30px;">
                <h3>Historique des Ventes</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Vente</th>
                        <th>Date</th>
                        <th>Montant Total</th>
                        <th>Statut</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($sale->total_amount, 0, ',', '.') }} XOF</td>
                            <td>
                                <span class="status {{ $sale->status === 'completed' ? 'completed' : ($sale->status === 'pending_credit' ? 'process' : 'pending') }}">
                                    {{ $sale->status === 'completed' ? 'Terminée' : ($sale->status === 'pending_credit' ? 'Crédit' : 'Annulée') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('sales.show', $sale->id) }}" style="color: var(--blue);"><i class='bx bx-info-circle'></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">Aucune vente enregistrée pour ce client.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="head" style="margin-top: 30px;">
                <h3>Historique des Crédits/Dettes</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Crédit</th>
                        <th>Montant Initial</th>
                        <th>Montant Payé</th>
                        <th>Montant Restant</th>
                        <th>Date Échéance</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->credits as $credit)
                        <tr>
                            <td>#{{ $credit->id }}</td>
                            <td>{{ number_format($credit->amount, 0, ',', '.') }} XOF</td>
                            <td>{{ number_format($credit->amount_paid, 0, ',', '.') }} XOF</td>
                            <td>{{ number_format($credit->amount - $credit->amount_paid, 0, ',', '.') }} XOF</td>
                            <td>{{ $credit->due_date ? $credit->due_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <span class="status {{ $credit->status === 'paid' ? 'completed' : ($credit->status === 'partially_paid' ? 'process' : 'pending') }}">
                                    {{ $credit->status === 'paid' ? 'Payé' : ($credit->status === 'partially_paid' ? 'Partiellement Payé' : 'En Attente') }}
                                </span>
                            </td>
                            <td>
                                {{-- Ajoutez ici des liens pour voir les détails d'un crédit ou enregistrer un paiement --}}
                                <a href="#" style="color: var(--blue); margin-right: 10px;" title="Voir détails crédit"><i class='bx bx-info-circle'></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">Aucun crédit/dette enregistré pour ce client.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
