@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Produits les Plus Vendus</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('reports.index') }}">Rapports</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('reports.index') }}">Retour</a></li>
            </ul>
        </div>
        <a href="{{ route('reports.export_pdf', ['reportType' => 'top-selling-products', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-download" style="background-color: var(--red);">
            <i class='bx bxs-file-pdf' ></i>
            <span class="text">Exporter PDF</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Top Produits du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h3>
                <form action="{{ route('reports.top_selling_products') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
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
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom du Produit</th>
                            <th>Quantité Totale Vendue</th>
                            <th>Chiffre d'Affaires Généré</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topProducts as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ number_format($product->total_quantity_sold, 0, ',', '.') }}</td>
                                <td>{{ number_format($product->total_revenue, 0, ',', '.') }} XOF</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">Aucun produit vendu pour cette période.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
