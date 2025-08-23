@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Rapport des Ventes Périodiques</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('reports.index') }}">Rapports</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('reports.index') }}">Retour</a></li>
            </ul>
        </div>
        {{-- Lien pour exporter en PDF --}}
        <a href="{{ route('reports.export_pdf', ['reportType' => 'periodic-sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-download" style="background-color: var(--red);">
            <i class='bx bxs-file-pdf' ></i> {{-- Icône PDF --}}
            <span class="text">Exporter PDF</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Ventes du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h3>
                <form action="{{ route('reports.periodic_sales') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
                    <label for="start_date" style="color: var(--dark);">Début :</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" required
                           style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                    <label for="end_date" style="color: var(--dark);">Fin :</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" required
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

                <h4 style="margin-bottom: 15px; color: var(--dark);">Détails des Ventes par Jour</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant Total</th>
                            <th>Nombre de Ventes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salesTrendData as $trend) {{-- C'est ici que le changement est crucial --}}
                            <tr>
                                <td>{{ $trend['date_formatted'] }}</td>
                                <td>{{ number_format($trend['total_amount'], 0, ',', '.') }} XOF</td>
                                <td>{{ $trend['number_of_sales'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px;">Aucune vente enregistrée pour cette période.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <h4 style="margin-top: 20px; margin-bottom: 15px; color: var(--dark);">Liste des Ventes Individuelles</h4>
                <table>
                    <thead>
                        <tr>
                            <th>ID Vente</th>
                            <th>Date & Heure</th>
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
                                <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
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
                                <td colspan="6" style="text-align: center; padding: 20px;">Aucune vente détaillée pour cette période.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
