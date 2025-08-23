@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Rapport des Crédits / Dettes</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('reports.index') }}">Rapports</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('reports.index') }}">Retour</a></li>
            </ul>
        </div>
        <a href="{{ route('reports.export_pdf', ['reportType' => 'credits-report']) }}" class="btn-download"  style="background-color: var(--red);">
            <i class='bx bxs-file-pdf' ></i> {{-- Icône PDF --}}
            <span class="text">Exporter PDF</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Vue d'Ensemble des Crédits / Dettes</h3>
            </div>

            <div style="padding: 20px;">
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Total des Crédits en Attente :</strong> <span style="color: var(--dark-grey); font-weight: 600;">{{ number_format($totalOutstandingCredit, 0, ',', '.') }} XOF</span>
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Total des Crédits Partiellement Payés :</strong> <span style="color: var(--yellow); font-weight: 600;">{{ number_format($totalPartiallyPaidCredit, 0, ',', '.') }} XOF</span>
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Total des Crédits en Retard :</strong> <span style="color: var(--red); font-weight: 600;">{{ number_format($totalOverdueCredit, 0, ',', '.') }} XOF</span>
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Total des Crédits Payés :</strong> <span style="color: var(--blue); font-weight: 600;">{{ number_format($totalCreditPaid, 0, ',', '.') }} XOF</span>
                </p>
                 <p style="margin-bottom: 20px; font-size: 1.2em; color: var(--dark);">
                    <strong>Dette Totale Actuelle des Clients :</strong> <span style="color: var(--red); font-weight: 700;">{{ number_format($overallTotalCreditDebt, 0, ',', '.') }} XOF</span>
                </p>

                <h4 style="margin-bottom: 15px; color: var(--dark);">Crédits par Statut</h4>
                @if ($creditsByStatus->isNotEmpty())
                    <ul>
                        @foreach ($creditsByStatus as $status => $amount)
                            <li style="margin-bottom: 5px; color: var(--dark);">
                                <strong>{{ match($status) { 'outstanding' => 'En Attente', 'partially_paid' => 'Partiellement Payé', 'paid' => 'Payé', 'overdue' => 'En Retard', default => $status } }} :</strong>
                                {{ number_format($amount, 0, ',', '.') }} XOF
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p style="color: var(--dark-grey);">Aucun crédit enregistré.</p>
                @endif


                <h4 style="margin-top: 20px; margin-bottom: 15px; color: var(--dark);">Top 5 Clients les Plus Endettés</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Nom du Client</th>
                            <th>Téléphone</th>
                            <th>Dette Actuelle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topIndebtedCustomers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone_number ?: 'N/A' }}</td>
                                <td><span class="status pending">{{ number_format($customer->total_credit_debt, 0, ',', '.') }} XOF</span></td>
                                <td><a href="{{ route('customers.show', $customer->id) }}" style="color: var(--blue);"><i class='bx bx-info-circle'></i></a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">Aucun client endetté actuellement.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <h4 style="margin-top: 20px; margin-bottom: 15px; color: var(--dark);">Détail de Tous les Crédits</h4>
                <table>
                    <thead>
                        <tr>
                            <th>ID Crédit</th>
                            <th>Client</th>
                            <th>Montant Initial</th>
                            <th>Montant Payé</th>
                            <th>Montant Restant</th>
                            <th>Date Échéance</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($credits as $credit)
                            <tr>
                                <td>#{{ $credit->id }}</td>
                                <td>{{ $credit->customer->name }}</td>
                                <td>{{ number_format($credit->amount, 0, ',', '.') }} XOF</td>
                                <td>{{ number_format($credit->amount_paid, 0, ',', '.') }} XOF</td>
                                <td>{{ number_format($credit->amount - $credit->amount_paid, 0, ',', '.') }} XOF</td>
                                <td>{{ $credit->due_date ? $credit->due_date->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <span class="status {{ $credit->status === 'paid' ? 'completed' : ($credit->status === 'partially_paid' ? 'process' : ($credit->status === 'overdue' ? 'pending' : 'default')) }}">
                                        @if ($credit->status === 'paid') Payé
                                        @elseif ($credit->status === 'partially_paid') Partiellement Payé
                                        @elseif ($credit->status === 'overdue') En Retard
                                        @else En Attente
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">Aucun crédit enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
