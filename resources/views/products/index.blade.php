@extends('layouts.app') {{-- Utilise votre layout principal --}}

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Produits</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('products.index') }}">Produits</a></li>
            </ul>
        </div>
        <a href="{{ route('products.create') }}" class="btn-download" style="background-color: var(--blue);">
            <i class='bx bx-plus' ></i>
            <span class="text">Ajouter un Produit</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Liste des Produits</h3>
                <form action="{{ route('products.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
                    <div class="form-input" style="margin-right: 10px;">
                        <input type="search" name="search" placeholder="Rechercher par nom..." value="{{ request('search') }}"
                               style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                    </div>
                    <select name="filter_stock" onchange="this.form.submit()"
                            style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="">Tous les stocks</option>
                        <option value="low" {{ request('filter_stock') == 'low' ? 'selected' : '' }}>Stock bas</option>
                    </select>
                    <button type="submit" class="search-btn" style="background-color: var(--blue); color: white; padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer;">
                        <i class='bx bx-search'></i>
                    </button>
                </form>
            </div>

            @if (session('success'))
                <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix Achat</th>
                        <th>Prix Vente</th>
                        <th>Stock</th>
                        <th>Alerte Min.</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="{{ $product->stock_quantity <= $product->min_stock_alert ? 'low-stock-row' : '' }}">
                            <td>
                                {{-- UTILISATION DE L'ACCESSEUR image_url qui gère le chemin et l'image par défaut --}}
                                <img src="{{ $product->image_url }}"
                                     alt="{{ $product->name }}"
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->purchase_price, 0, ',', '.') }} XOF</td>
                            <td>{{ number_format($product->sale_price, 0, ',', '.') }} XOF</td>
                            <td>
                                <span class="status {{ $product->stock_quantity <= $product->min_stock_alert ? 'pending' : 'completed' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td>{{ $product->min_stock_alert }}</td>
                            <td>
                                {{-- Bouton Modifier --}}
                                <a href="{{ route('products.edit', $product->id) }}" style="color: var(--orange); margin-right: 10px;"><i class='bx bx-edit'></i></a>

                                {{-- Formulaire de suppression avec confirmation --}}
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE') {{-- Indique que c'est une requête DELETE --}}
                                    <button type="submit" style="background: none; border: none; color: var(--red); cursor: pointer; padding: 0;"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.');">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">Aucun produit trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $products->links() }} {{-- Affiche les liens de pagination --}}
            </div>
        </div>
    </div>

    <style>
        /* Styles pour les lignes de stock bas */
        .low-stock-row {
            background-color: var(--light-orange); /* Couleur d'arrière-plan pour les produits en stock bas */
        }
        .low-stock-row .status.pending {
            background-color: var(--red); /* Rouge pour le statut "stock bas" */
        }
        /* Assurez-vous que votre style.css définit les couleurs comme --light-orange, --red, etc. */
    </style>
@endsection
