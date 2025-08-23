@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Modifier Crédit #{{ $credit->id }}</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('credits.index') }}">Crédits</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('credits.edit', $credit->id) }}">Modifier</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Modifier les Informations du Crédit</h3>
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

            <form action="{{ route('credits.update', $credit->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Utilise la méthode PUT pour la mise à jour --}}

                <div style="margin-bottom: 15px;">
                    <label for="customer_id" style="display: block; margin-bottom: 5px; color: var(--dark);">Client :</label>
                    <select id="customer_id" name="customer_id" required
                            style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="">-- Sélectionner un client --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $credit->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone_number }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="amount" style="display: block; margin-bottom: 5px; color: var(--dark);">Montant du Crédit (XOF) :</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount', $credit->amount) }}" required step="0.01" min="0"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    <p style="font-size: 0.8em; color: var(--dark-grey); margin-top: 5px;">Montant déjà payé: {{ number_format($credit->amount_paid, 0, ',', '.') }} XOF. Le nouveau montant doit être supérieur ou égal.</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="due_date" style="display: block; margin-bottom: 5px; color: var(--dark);">Date d'Échéance (facultatif) :</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $credit->due_date ? $credit->due_date->format('Y-m-d') : '') }}"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="sale_id" style="display: block; margin-bottom: 5px; color: var(--dark);">Lier à une Vente (facultatif) :</label>
                    <select id="sale_id" name="sale_id"
                            style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="">-- Aucune vente liée --</option>
                        @foreach($sales as $sale)
                            <option value="{{ $sale->id }}" {{ old('sale_id', $credit->sale_id) == $sale->id ? 'selected' : '' }}>
                                Vente #{{ $sale->id }} ({{ number_format($sale->total_amount, 0, ',', '.') }} XOF - {{ $sale->sale_date->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="description" style="display: block; margin-bottom: 5px; color: var(--dark);">Description (facultatif) :</label>
                    <textarea id="description" name="description" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">{{ old('description', $credit->description) }}</textarea>
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="status" style="display: block; margin-bottom: 5px; color: var(--dark);">Statut du Crédit :</label>
                    <select id="status" name="status" required
                            style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="outstanding" {{ old('status', $credit->status) == 'outstanding' ? 'selected' : '' }}>En Attente</option>
                        <option value="partially_paid" {{ old('status', $credit->status) == 'partially_paid' ? 'selected' : '' }} {{ $credit->amount_paid == 0 ? 'disabled' : '' }}>Partiellement Payé</option>
                        <option value="paid" {{ old('status', $credit->status) == 'paid' ? 'selected' : '' }} {{ $credit->amount_paid < $credit->amount ? 'disabled' : '' }}>Payé</option>
                        <option value="overdue" {{ old('status', $credit->status) == 'overdue' ? 'selected' : '' }}>En Retard</option>
                    </select>
                    <p style="font-size: 0.8em; color: var(--dark-grey); margin-top: 5px;">Certains statuts peuvent être désactivés en fonction du montant payé.</p>
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Mettre à jour le Crédit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
