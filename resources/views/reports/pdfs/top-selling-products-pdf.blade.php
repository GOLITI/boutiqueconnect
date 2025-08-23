<!DOCTYPE html>
<html>
<head>
    <title>Rapport des Produits les Plus Vendus - {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Produits les Plus Vendus</h1>
        <p>Date du Rapport : {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <p>Pour la Période du : <span class="text-bold">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</span> au <span class="text-bold">{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom du Produit</th>
                <th class="text-right">Quantité Totale Vendue</th>
                <th class="text-right">Chiffre d'Affaires Généré</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topProducts as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td class="text-right">{{ number_format($product->total_quantity_sold, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($product->total_revenue, 0, ',', '.') }} XOF</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aucun produit vendu pour cette période.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
