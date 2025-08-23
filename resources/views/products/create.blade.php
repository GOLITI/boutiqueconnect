@extends('layouts.app') {{-- Utilise votre layout principal --}}

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Ajouter un Produit</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('products.index') }}">Produits</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('products.create') }}">Ajouter</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Nouveau Produit</h3>
            </div>

            @if ($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- IMPORTANT: enctype="multipart/form-data" est nécessaire pour l'upload de fichiers --}}
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Nom du Produit :</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="description" style="display: block; margin-bottom: 5px; color: var(--dark);">Description (facultatif) :</label>
                    <textarea id="description" name="description" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">{{ old('description') }}</textarea>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label for="purchase_price" style="display: block; margin-bottom: 5px; color: var(--dark);">Prix d'Achat (XOF) :</label>
                        <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required step="0.01"
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                    <div style="flex: 1;">
                        <label for="sale_price" style="display: block; margin-bottom: 5px; color: var(--dark);">Prix de Vente (XOF) :</label>
                        <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" required step="0.01"
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label for="stock_quantity" style="display: block; margin-bottom: 5px; color: var(--dark);">Quantité en Stock :</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" required min="0"
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                    <div style="flex: 1;">
                        <label for="min_stock_alert" style="display: block; margin-bottom: 5px; color: var(--dark);">Seuil d'Alerte Stock Bas :</label>
                        <input type="number" id="min_stock_alert" name="min_stock_alert" value="{{ old('min_stock_alert') }}" required min="0"
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="barcode" style="display: block; margin-bottom: 5px; color: var(--dark);">Code-barres (facultatif) :</label>
                    <input type="text" id="barcode" name="barcode" value="{{ old('barcode') }}"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="image" style="display: block; margin-bottom: 5px; color: var(--dark);">Image du Produit (facultatif) :</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Ajouter le Produit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
