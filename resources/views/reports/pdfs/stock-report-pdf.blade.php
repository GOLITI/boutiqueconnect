<!DOCTYPE html>
<html>
<head>
    <title>Rapport de Stock</title>
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
        .low-stock-row { background-color: #ffe0d3; } /* light-orange from css */
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Stock</h1>
        <p>Date du Rapport : {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <p><span class="text-bold">Valeur Totale du Stock (Prix d'Achat) :</span> {{ number_format($totalStockValuePurchase, 0, ',', '.') }} XOF</p>
        <p><span class="text-bold">Valeur Totale du Stock (Prix de Vente) :</span> {{ number_format($totalStockValueSale, 0, ',', '.') }} XOF</p>
    </div>

    <h3>Produits en Stock Bas (ou rupture)</h3>
    <table>
        <thead>
            <tr>
                <th>Nom du Produit</th>
                <th class="text-right">Stock Actuel</th>
                <th class="text-right">Alerte Minimale</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lowStockProducts as $product)
                <tr class="low-stock-row">
                    <td>{{ $product->name }}</td>
                    <td class="text-right">{{ $product->stock_quantity }}</td>
                    <td class="text-right">{{ $product->min_stock_alert }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Aucun produit en stock bas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Détails de Tous les Produits en Stock</h3>
    <table>
        <thead>
            <tr>
                <th>Nom du Produit</th>
                <th class="text-right">Stock Actuel</th>
                <th class="text-right">Prix d'Achat</th>
                <th class="text-right">Prix de Vente</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td class="text-right">{{ $product->stock_quantity }}</td>
                    <td class="text-right">{{ number_format($product->purchase_price, 0, ',', '.') }} XOF</td>
                    <td class="text-right">{{ number_format($product->sale_price, 0, ',', '.') }} XOF</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aucun produit enregistré.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
