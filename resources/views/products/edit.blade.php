@extends('layouts.app') {{-- Utilise votre layout principal --}}

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Modifier le Produit</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('products.index') }}">Produits</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('products.index', $product->id) }}">Annuler</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Modifier : {{ $product->name }}</h3>
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
            {{-- Utilisation de POST avec @method('PUT') pour simuler une requête PUT --}}
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- CORRECTION ICI : Doit être PUT pour correspondre à la route --}}

                <div style="margin-bottom: 25px; text-align: center;">
                    <label for="image" style="display: block; margin-bottom: 10px; color: var(--dark); font-weight: 600;">
                        Image du Produit :
                    </label>
                    <div style="position: relative; display: inline-block; cursor: pointer;">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('img/default-product.png') }}"
                             alt="{{ $product->name }}"
                             style="width: 120px; height: 120px; border-radius: 8px; object-fit: cover; border: 3px solid var(--blue); box-shadow: 0 4px 8px rgba(0,0,0,0.15);">
                        <div style="position: absolute; bottom: 0; right: 0; background-color: var(--blue); color: white; border-radius: 50%; padding: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: background-color 0.3s ease;">
                            <i class='bx bxs-camera' style="font-size: 20px;"></i>
                        </div>
                        <input type="file" id="image" name="image" accept="image/*" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    </div>
                    <p style="font-size: 0.8em; color: var(--dark-grey); margin-top: 10px;">Cliquez sur l'image pour changer l'image du produit.</p>

                    @if ($product->image)
                        <div style="margin-top: 10px; display: flex; align-items: center; justify-content: center;">
                            <input type="checkbox" id="remove_image" name="remove_image" value="1" style="margin-right: 5px;">
                            <label for="remove_image" style="color: var(--red); font-size: 0.9em;">Supprimer l'image actuelle</label>
                        </div>
                    @endif
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Nom du Produit :</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="description" style="display: block; margin-bottom: 5px; color: var(--dark);">Description (facultatif) :</label>
                    <textarea id="description" name="description" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">{{ old('description', $product->description) }}</textarea>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label for="purchase_price" style="display: block; margin-bottom: 5px; color: var(--dark);">Prix d'Achat (XOF) :</label>
                        <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required step="0.01"
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                    <div style="flex: 1;">
                        <label for="sale_price" style="display: block; margin-bottom: 5px; color: var(--dark);">Prix de Vente (XOF) :</label>
                        <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" required step="0.01"
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label for="stock_quantity" style="display: block; margin-bottom: 5px; color: var(--dark);">Quantité en Stock :</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0"
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                    <div style="flex: 1;">
                        <label for="min_stock_alert" style="display: block; margin-bottom: 5px; color: var(--dark);">Seuil d'Alerte Stock Bas :</label>
                        <input type="number" id="min_stock_alert" name="min_stock_alert" value="{{ old('min_stock_alert', $product->min_stock_alert) }}" required min="0"
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="barcode" style="display: block; margin-bottom: 5px; color: var(--dark);">Code-barres (facultatif) :</label>
                    <input type="text" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Mettre à jour le Produit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
