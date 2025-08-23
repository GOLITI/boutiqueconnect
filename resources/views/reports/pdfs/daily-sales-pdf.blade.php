<!DOCTYPE html>
<html>
<head>
    <title>Rapport des Ventes Journalières - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</title>
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
        .status { padding: 3px 8px; border-radius: 5px; font-size: 8px; font-weight: bold; color: white; display: inline-block; }
        .status.completed { background-color: #3C91E6; } /* blue */
        .status.process { background-color: #FFCE26; } /* yellow */
        .status.pending { background-color: #FD7238; } /* orange */
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Ventes Journalières</h1>
        <p>Date du Rapport : {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <p>Pour la Journée du : <span class="text-bold">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span></p>
    </div>

    <div class="summary">
        <p><span class="text-bold">Montant Total des Ventes :</span> {{ number_format($totalSalesAmount, 0, ',', '.') }} XOF</p>
        <p><span class="text-bold">Nombre de Transactions :</span> {{ $numberOfTransactions }}</p>
    </div>

    <h3>Détails des Ventes</h3>
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
                    <td class="text-right">{{ number_format($sale->total_amount, 0, ',', '.') }} XOF</td>
                    <td>{{ $sale->payment_method }}</td>
                    <td>
                        <span class="status {{ $sale->status === 'completed' ? 'completed' : ($sale->status === 'pending_credit' ? 'process' : 'pending') }}">
                            {{ $sale->status === 'completed' ? 'Terminée' : ($sale->status === 'pending_credit' ? 'Crédit' : 'Annulée') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Aucune vente enregistrée pour cette date.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Ventes par Méthode de Paiement</h3>
    <table>
        <thead>
            <tr>
                <th>Méthode de Paiement</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($salesByPaymentMethod as $method => $amount)
                <tr>
                    <td>{{ $method }}</td>
                    <td class="text-right">{{ number_format($amount, 0, ',', '.') }} XOF</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Aucune vente par méthode de paiement pour cette date.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
