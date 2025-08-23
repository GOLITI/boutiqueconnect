@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Rapport de Stock</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('reports.index') }}">Rapports</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('reports.index') }}">Retour</a></li>
            </ul>
        </div>
        <a href="{{ route('reports.export_pdf', ['reportType' => 'stock-report']) }}" class="btn-download"  style="background-color: var(--red);">
            <i class='bx bxs-file-pdf' ></i> {{-- Icône PDF --}}
            <span class="text">Exporter PDF</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Vue d'Ensemble du Stock</h3>
            </div>

            <div style="padding: 20px;">
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Valeur Totale du Stock (Prix d'Achat) :</strong> <span style="color: var(--blue); font-weight: 600;">{{ number_format($totalStockValuePurchase, 0, ',', '.') }} XOF</span>
                </p>
                <p style="margin-bottom: 20px; font-size: 1.1em; color: var(--dark);">
                    <strong>Valeur Totale du Stock (Prix de Vente) :</strong> <span style="color: var(--orange); font-weight: 600;">{{ number_format($totalStockValueSale, 0, ',', '.') }} XOF</span>
                </p>

                <h4 style="margin-bottom: 15px; color: var(--dark);">Produits en Stock Bas (ou rupture)</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Nom du Produit</th>
                            <th>Stock Actuel</th>
                            <th>Alerte Minimale</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $product)
                            <tr class="low-stock-row">
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>{{ $product->min_stock_alert }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px;">Aucun produit en stock bas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <h4 style="margin-top: 20px; margin-bottom: 15px; color: var(--dark);">Détails de Tous les Produits en Stock</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Nom du Produit</th>
                            <th>Stock Actuel</th>
                            <th>Prix d'Achat</th>
                            <th>Prix de Vente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>{{ number_format($product->purchase_price, 0, ',', '.') }} XOF</td>
                                <td>{{ number_format($product->sale_price, 0, ',', '.') }} XOF</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">Aucun produit enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
