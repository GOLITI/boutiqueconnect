<!DOCTYPE html>
<html>
<head>
    <title>Résumé du Tableau de Bord - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; }
        h1, h2, h3 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary-box {
            border: 1px solid #eee;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .summary-box p { margin: 5px 0; font-size: 1.1em; }
        .text-bold { font-weight: bold; }
        .text-right { text-align: right; }
        .status { padding: 3px 8px; border-radius: 5px; font-size: 8px; font-weight: bold; color: white; display: inline-block; }
        .status.completed { background-color: #3C91E6; } /* blue */
        .status.process { background-color: #FFCE26; } /* yellow */
        .status.pending { background-color: #FD7238; } /* orange */
        .header { text-align: center; margin-bottom: 30px; }
        .low-stock-item { background-color: #ffe0d3; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Résumé du Tableau de Bord</h1>
        <p>Généré le : {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary-box">
        <h2>Statistiques Clés</h2>
        <p><span class="text-bold">Ventes du Jour :</span> {{ number_format($todaySalesAmount, 0, ',', '.') }} XOF</p>
        <p><span class="text-bold">Clients Enregistrés :</span> {{ $totalCustomers }}</p>
        <p><span class="text-bold">Produits en Stock Bas :</span> {{ $lowStockProductsCount }}</p>
    </div>

    <h2>Ventes Récentes</h2>
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Date Vente</th>
                <th>Montant Total</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentSales as $sale)
                <tr>
                    <td>{{ $sale->customer ? $sale->customer->name : 'Client de passage' }}</td>
                    <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                    <td class="text-right">{{ number_format($sale->total_amount, 0, ',', '.') }} XOF</td>
                    <td>
                        <span class="status {{ $sale->status === 'completed' ? 'completed' : ($sale->status === 'pending_credit' ? 'pending' : 'process') }}">
                            {{ $sale->status === 'completed' ? 'Payé' : ($sale->status === 'pending_credit' ? 'Crédit' : 'Partiel') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aucune vente récente à afficher.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Alertes Stock Bas</h2>
    <table>
        <thead>
            <tr>
                <th>Nom du Produit</th>
                <th>Quantité Restante</th>
                <th>Seuil d'Alerte</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lowStockProductsList as $product)
                <tr class="low-stock-item">
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ $product->min_stock_alert }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Aucune alerte de stock bas !</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
