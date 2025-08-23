@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Ajouter un Crédit</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('credits.index') }}">Crédits</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('credits.create') }}">Ajouter</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Enregistrer un Nouveau Crédit</h3>
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

            <form action="{{ route('credits.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 15px;">
                    <label for="customer_id" style="display: block; margin-bottom: 5px; color: var(--dark);">Client :</label>
                    <select id="customer_id" name="customer_id" required
                            style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="">-- Sélectionner un client --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $selectedCustomer->id ?? '') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone_number }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="amount" style="display: block; margin-bottom: 5px; color: var(--dark);">Montant du Crédit (XOF) :</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" required step="0.01" min="0"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="due_date" style="display: block; margin-bottom: 5px; color: var(--dark);">Date d'Échéance (facultatif) :</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="sale_id" style="display: block; margin-bottom: 5px; color: var(--dark);">Lier à une Vente (facultatif) :</label>
                    <select id="sale_id" name="sale_id"
                            style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="">-- Aucune vente liée --</option>
                        @foreach($sales as $sale)
                            <option value="{{ $sale->id }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
                                Vente #{{ $sale->id }} ({{ number_format($sale->total_amount, 0, ',', '.') }} XOF - {{ $sale->sale_date->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="description" style="display: block; margin-bottom: 5px; color: var(--dark);">Description (facultatif) :</label>
                    <textarea id="description" name="description" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">{{ old('description') }}</textarea>
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Enregistrer le Crédit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
