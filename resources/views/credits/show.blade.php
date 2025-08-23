@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Détails du Crédit #{{ $credit->id }}</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('credits.index') }}">Crédits</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('credits.show', $credit->id) }}">Détails</a></li>
            </ul>
        </div>
        <a href="{{ route('credits.index') }}" class="btn-download" style="background-color: var(--dark-grey);">
            <i class='bx bx-arrow-back' ></i>
            <span class="text">Retour aux Crédits</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Informations du Crédit</h3>
            </div>

            <div style="padding: 20px;">
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>ID Crédit :</strong> #{{ $credit->id }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Client :</strong> <a href="{{ route('customers.show', $credit->customer->id) }}" style="color: var(--blue); text-decoration: underline;">{{ $credit->customer->name }} ({{ $credit->customer->phone_number }})</a>
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Montant Initial :</strong> <span style="color: var(--blue); font-weight: 600;">{{ number_format($credit->amount, 0, ',', '.') }} XOF</span>
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Montant Payé :</strong> {{ number_format($credit->amount_paid, 0, ',', '.') }} XOF
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Montant Restant :</strong> <span style="color: var(--red); font-weight: 600;">{{ number_format($credit->amount - $credit->amount_paid, 0, ',', '.') }} XOF</span>
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Date d'Échéance :</strong> {{ $credit->due_date ? $credit->due_date->format('d/m/Y') : 'N/A' }}
                </p>
                @if ($credit->sale)
                    <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                        <strong>Lié à la Vente :</strong> <a href="{{ route('sales.show', $credit->sale->id) }}" style="color: var(--blue); text-decoration: underline;">#{{ $credit->sale->id }} ({{ number_format($credit->sale->total_amount, 0, ',', '.') }} XOF)</a>
                    </p>
                @endif
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Description :</strong> {{ $credit->description ?: 'Aucune description.' }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Statut :</strong>
                    <span class="status {{ $credit->status === 'paid' ? 'completed' : ($credit->status === 'partially_paid' ? 'process' : ($credit->status === 'overdue' ? 'pending' : 'default')) }}">
                        @if ($credit->status === 'paid') Payé
                        @elseif ($credit->status === 'partially_paid') Partiellement Payé
                        @elseif ($credit->status === 'overdue') En Retard
                        @else En Attente
                        @endif
                    </span>
                </p>
            </div>

            <div class="head" style="margin-top: 30px;">
                <h3>Historique des Paiements</h3>
                @if ($credit->amount_paid < $credit->amount && $credit->status !== 'paid')
                    <a href="{{ route('credits.show_record_payment', $credit->id) }}" class="btn-download" style="background-color: var(--green); margin-left: auto;">
                        <i class='bx bx-plus' ></i>
                        <span class="text">Enregistrer un Paiement</span>
                    </a>
                @endif
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Paiement</th>
                        <th>Montant Payé</th>
                        <th>Date Paiement</th>
                        <th>Méthode</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($credit->payments as $payment)
                        <tr>
                            <td>#{{ $payment->id }}</td>
                            <td>{{ number_format($payment->amount_paid, 0, ',', '.') }} XOF</td>
                            <td>{{ $payment->payment_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $payment->payment_method }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px;">Aucun paiement enregistré pour ce crédit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 30px; text-align: center;">
                <a href="{{ route('credits.edit', $credit->id) }}" class="btn-download" style="background-color: var(--orange); margin-right: 10px;">
                    <i class='bx bx-edit' ></i>
                    <span class="text">Modifier le Crédit</span>
                </a>
                <form action="{{ route('credits.destroy', $credit->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background-color: var(--red); color: white; padding: 12px 25px; border-radius: 8px; border: none; font-size: 16px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce crédit ? Cette action est irréversible et affectera la dette du client.');">
                        <i class='bx bx-trash'></i> Supprimer le Crédit
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
