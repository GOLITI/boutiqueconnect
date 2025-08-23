<!DOCTYPE html>
<html>
<head>
    <title>Rapport des Crédits / Dettes</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; }
        h1, h2, h3 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary { margin-bottom: 20px; }
        .summary p { margin: 5px 0; }
        .text-bold { font-weight: bold; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 30px; }
        .status { padding: 3px 8px; border-radius: 5px; font-size: 8px; font-weight: bold; color: white; display: inline-block; }
        .status.completed { background-color: #3C91E6; } /* blue - Payé */
        .status.process { background-color: #FFCE26; } /* yellow - Partiellement Payé */
        .status.pending { background-color: #DB504A; } /* red - En Retard / En Attente */
        .status.default { background-color: #AAAAAA; } /* dark-grey - En Attente */
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Crédits / Dettes</h1>
        <p>Date du Rapport : {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <p><span class="text-bold">Total des Crédits en Attente :</span> {{ number_format($totalOutstandingCredit, 0, ',', '.') }} XOF</p>
        <p><span class="text-bold">Total des Crédits Partiellement Payés :</span> {{ number_format($totalPartiallyPaidCredit, 0, ',', '.') }} XOF</p>
        <p><span class="text-bold">Total des Crédits en Retard :</span> {{ number_format($totalOverdueCredit, 0, ',', '.') }} XOF</p>
        <p><span class="text-bold">Total des Crédits Payés :</span> {{ number_format($totalCreditPaid, 0, ',', '.') }} XOF</p>
        <p style="font-size: 1.1em;"><span class="text-bold">Dette Totale Actuelle des Clients :</span> {{ number_format($overallTotalCreditDebt, 0, ',', '.') }} XOF</p>
    </div>

    <h3>Crédits par Statut</h3>
    <table>
        <thead>
            <tr>
                <th>Statut</th>
                <th class="text-right">Montant (Solde Restant)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($creditsByStatus as $status => $amount)
                <tr>
                    <td>
                        {{ match($status) { 'outstanding' => 'En Attente', 'partially_paid' => 'Partiellement Payé', 'paid' => 'Payé', 'overdue' => 'En Retard', default => $status } }}
                    </td>
                    <td class="text-right">{{ number_format($amount, 0, ',', '.') }} XOF</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Aucun crédit enregistré.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Top 5 Clients les Plus Endettés</h3>
    <table>
        <thead>
            <tr>
                <th>Nom du Client</th>
                <th>Téléphone</th>
                <th class="text-right">Dette Actuelle</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topIndebtedCustomers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone_number ?: 'N/A' }}</td>
                    <td class="text-right">{{ number_format($customer->total_credit_debt, 0, ',', '.') }} XOF</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Aucun client endetté actuellement.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Détail de Tous les Crédits</h3>
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
                    <td class="text-right">{{ number_format($credit->amount, 0, ',', '.') }} XOF</td>
                    <td class="text-right">{{ number_format($credit->amount_paid, 0, ',', '.') }} XOF</td>
                    <td class="text-right">{{ number_format($credit->amount - $credit->amount_paid, 0, ',', '.') }} XOF</td>
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
                    <td colspan="7">Aucun crédit enregistré.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
