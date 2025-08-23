@extends('layouts.app') {{-- Dit que cette vue utilise le layout app.blade.php --}}

@section('content') {{-- Définit la section 'content' pour le layout --}}
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            @auth
                <p>Bienvenue, {{ $user->name }} !</p> {{-- Affichage du nom de l'utilisateur connecté --}}
            @endauth
            <ul class="breadcrumb">
                <li>
                    <a href="#">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right' ></i></li>
            </ul>
        </div>
        <a href="{{ route('reports.export_pdf', ['reportType' => 'dashboard-summary']) }}"  class="btn-download" style="background-color: var(--red);">
            <i class='bx bxs-file-pdf' ></i>
            <span class="text">Télécharger Rapport PDF</span>
        </a>
    </div>

    <ul class="box-info">
        <li>
            <i class='bx bxs-dollar-circle' ></i>
            <span class="text">
                <h3>XOF {{ number_format($todaySalesAmount, 0, ',', '.') }}</h3> {{-- Donnée dynamique --}}
                <p>Ventes du Jour</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-group' ></i>
            <span class="text">
                <h3>{{ $totalCustomers }}</h3> {{-- Donnée dynamique --}}
                <p>Clients Enregistrés</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-box' ></i>
            <span class="text">
                <h3>{{ $lowStockProductsCount }}</h3> {{-- Donnée dynamique --}}
                <p>Produits Stock Bas</p>
            </span>
        </li>
    </ul>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Ventes Récentes</h3>
                <i class='bx bx-search' ></i>
                <i class='bx bx-filter' ></i>
            </div>
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
                    @forelse ($recentSales as $sale) {{-- Boucle sur les ventes récentes --}}
                        <tr>
                            <td>
                                <p>{{ $sale->customer ? $sale->customer->name : 'Client de passage' }}</p> {{-- Nom du client --}}
                            </td>
                            <td>{{ $sale->sale_date->format('d-m-Y') }}</td> {{-- Date formatée --}}
                            <td>{{ number_format($sale->total_amount, 0, ',', '.') }} XOF</td> {{-- Montant total --}}
                            <td>
                                <span class="status {{ $sale->status === 'completed' ? 'completed' : ($sale->status === 'pending_credit' ? 'pending' : 'process') }}">
                                    {{ $sale->status === 'completed' ? 'Payé' : ($sale->status === 'pending_credit' ? 'Crédit' : 'Partiel') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Aucune vente récente à afficher.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="todo">
            <div class="head">
                <h3>Alertes Stock Bas</h3>
                <i class='bx bx-plus' ></i>
                <i class='bx bx-filter' ></i>
            </div>
            <ul class="todo-list">
                @forelse ($lowStockProductsList as $product) {{-- Boucle sur les produits en stock bas --}}
                    <li class="not-completed">
                        <p>{{ $product->name }} ({{ $product->stock_quantity }} restants)</p>
                        <i class='bx bx-dots-vertical-rounded' ></i>
                    </li>
                @empty
                    <li class="completed"> {{-- Utilisez la classe 'completed' pour indiquer pas d'alertes --}}
                        <p>Aucune alerte de stock bas !</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
