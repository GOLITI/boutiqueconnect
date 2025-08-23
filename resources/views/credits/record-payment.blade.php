@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Enregistrer un Paiement</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('credits.index') }}">Crédits</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('credits.show', $credit->id) }}">Détails Crédit #{{ $credit->id }}</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('credits.show_record_payment', $credit->id) }}">Paiement</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Enregistrer un Paiement pour le Crédit #{{ $credit->id }}</h3>
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

            <div style="padding: 20px;">
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Client :</strong> {{ $credit->customer->name }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Montant Initial :</strong> {{ number_format($credit->amount, 0, ',', '.') }} XOF
                </p>
                <p style="margin-bottom: 20px; font-size: 1.1em; color: var(--dark);">
                    <strong>Montant Restant Dû :</strong> <span style="color: var(--red); font-weight: 600;">{{ number_format($credit->amount - $credit->amount_paid, 0, ',', '.') }} XOF</span>
                </p>
            </div>

            <form action="{{ route('credits.record_payment', $credit->id) }}" method="POST">
                @csrf

                <div style="margin-bottom: 15px;">
                    <label for="amount_paid" style="display: block; margin-bottom: 5px; color: var(--dark);">Montant du Paiement (XOF) :</label>
                    <input type="number" id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}" required step="0.01" min="0.01" max="{{ $credit->amount - $credit->amount_paid }}"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    <p style="font-size: 0.8em; color: var(--dark-grey); margin-top: 5px;">Le montant maximum que vous pouvez enregistrer est de {{ number_format($credit->amount - $credit->amount_paid, 0, ',', '.') }} XOF.</p>
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="payment_method" style="display: block; margin-bottom: 5px; color: var(--dark);">Méthode de Paiement :</label>
                    <select id="payment_method" name="payment_method" required
                            style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Mobile Money" {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Virement Bancaire</option>
                    </select>
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Enregistrer le Paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
