@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Rapport des Ventes Journalières</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('reports.index') }}">Rapports</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('reports.index') }}">Retour</a></li>
            </ul>
        </div>
        <a href="{{ route('reports.export_pdf', ['reportType' => 'daily-sales', 'date' => $date]) }}" class="btn-download"  style="background-color: var(--red);">
           <i class='bx bxs-file-pdf' ></i> {{-- Icône PDF --}}
            <span class="text">Exporter PDF</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Ventes du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>
                <form action="{{ route('reports.daily_sales') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
                    <input type="date" name="date" value="{{ $date }}"
                           style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                    <button type="submit" class="search-btn" style="background-color: var(--blue); color: white; padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer;">
                        <i class='bx bx-search'></i> Afficher
                    </button>
                </form>
            </div>

            <div style="padding: 20px;">
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Montant Total des Ventes :</strong> <span style="color: var(--blue); font-weight: 600;">{{ number_format($totalSalesAmount, 0, ',', '.') }} XOF</span>
                </p>
                <p style="margin-bottom: 20px; font-size: 1.1em; color: var(--dark);">
                    <strong>Nombre de Transactions :</strong> <span style="color: var(--orange); font-weight: 600;">{{ $numberOfTransactions }}</span>
                </p>

                <h4 style="margin-bottom: 15px; color: var(--dark);">Détails des Ventes</h4>
                <table>
                    <thead>
                        <tr>
                            <th>ID Vente</th>
                            <th>Heure</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Méthode de Paiement</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            <tr>
                                <td>#{{ $sale->id }}</td>
                                <td>{{ $sale->sale_date->format('H:i') }}</td>
                                <td>{{ $sale->customer ? $sale->customer->name : 'N/A' }}</td>
                                <td>{{ number_format($sale->total_amount, 0, ',', '.') }} XOF</td>
                                <td>{{ $sale->payment_method }}</td>
                                <td>
                                    <span class="status {{ $sale->status === 'completed' ? 'completed' : ($sale->status === 'pending_credit' ? 'process' : 'pending') }}">
                                        {{ $sale->status === 'completed' ? 'Terminée' : ($sale->status === 'pending_credit' ? 'Crédit' : 'Annulée') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">Aucune vente enregistrée pour cette date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <h4 style="margin-top: 20px; margin-bottom: 15px; color: var(--dark);">Ventes par Méthode de Paiement</h4>
                @if ($salesByPaymentMethod->isNotEmpty())
                    <ul>
                        @foreach ($salesByPaymentMethod as $method => $amount)
                            <li style="margin-bottom: 5px; color: var(--dark);"><strong>{{ $method }} :</strong> {{ number_format($amount, 0, ',', '.') }} XOF</li>
                        @endforeach
                    </ul>
                @else
                    <p style="color: var(--dark-grey);">Aucune vente par méthode de paiement pour cette date.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
