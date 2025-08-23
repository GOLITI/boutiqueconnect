@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Modifier Vente #{{ $sale->id }}</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('sales.index') }}">Ventes</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('sales.edit', $sale->id) }}">Modifier</a></li>
            </ul>
        </div>
        <a href="{{ route('sales.index') }}" class="btn-download" style="background-color: var(--dark-grey);">
            <i class='bx bx-arrow-back' ></i>
            <span class="text">Retour aux Ventes</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Modifier les Informations de la Vente</h3>
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

            @if (session('error'))
                <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <form id="saleEditForm" action="{{ route('sales.update', $sale->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Utilise la méthode PUT pour la mise à jour --}}

                <div style="margin-bottom: 20px; border: 1px solid var(--grey); padding: 15px; border-radius: 10px;">
                    <h4 style="margin-bottom: 15px; color: var(--dark);">Informations Client</h4>
                    <div style="margin-bottom: 15px;">
                        <label for="customer_type" style="display: block; margin-bottom: 5px; color: var(--dark);">Type de Client :</label>
                        <select id="customer_type" name="customer_type"
                                style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                            <option value="existing" {{ old('customer_type', $sale->customer_id ? 'existing' : 'new_temp') == 'existing' ? 'selected' : '' }}>Client existant</option>
                            <option value="new_temp" {{ old('customer_type', $sale->customer_id ? 'existing' : 'new_temp') == 'new_temp' ? 'selected' : '' }}>Nouveau client (temporaire)</option>
                        </select>
                    </div>

                    <div id="existing_customer_fields" style="margin-bottom: 15px;">
                        <label for="customer_id" style="display: block; margin-bottom: 5px; color: var(--dark);">Sélectionner un client :</label>
                        <select id="customer_id" name="customer_id"
                                style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                            <option value="">-- Aucun client --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $sale->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="new_customer_fields" style="margin-bottom: 15px;">
                        <div style="margin-bottom: 15px;">
                            <label for="client_name_new" style="display: block; margin-bottom: 5px; color: var(--dark);">Nom du nouveau client :</label>
                            <input type="text" id="client_name_new" name="client_name_new" value="{{ old('client_name_new', $sale->customer ? $sale->customer->name : '') }}"
                                   style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                        </div>
                        <div>
                            <label for="client_phone_new" style="display: block; margin-bottom: 5px; color: var(--dark);">Téléphone du nouveau client :</label>
                            <input type="text" id="client_phone_new" name="client_phone_new" value="{{ old('client_phone_new', $sale->customer ? $sale->customer->phone_number : '') }}"
                                   style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px; border: 1px solid var(--grey); padding: 15px; border-radius: 10px;">
                    <h4 style="margin-bottom: 15px; color: var(--dark);">Détails de la Vente</h4>
                    <p style="margin-bottom: 10px; font-weight: 600; color: var(--dark);">
                        Montant Total de la Vente : <span style="color: var(--blue);">{{ number_format($sale->total_amount, 0, ',', '.') }} XOF</span>
                        <input type="hidden" name="total_amount" value="{{ $sale->total_amount }}"> {{-- Champ caché pour le total --}}
                    </p>
                    <div style="margin-bottom: 15px;">
                        <label for="paid_amount" style="display: block; margin-bottom: 5px; color: var(--dark);">Montant Payé (XOF) :</label>
                        <input type="number" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', $sale->paid_amount) }}" step="0.01" required
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <p style="color: var(--dark); font-weight: 600;">Monnaie à Rendre : <span id="change_amount_display" style="color: var(--green);">{{ number_format($sale->change_amount, 0, ',', '.') }} XOF</span></p>
                        <input type="hidden" name="change_amount" id="form_change_amount" value="{{ $sale->change_amount }}">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="payment_method" style="display: block; margin-bottom: 5px; color: var(--dark);">Mode de Paiement :</label>
                        <select id="payment_method" name="payment_method"
                                style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                            <option value="Cash" {{ old('payment_method', $sale->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Mobile Money" {{ old('payment_method', $sale->payment_method) == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="Credit" {{ old('payment_method', $sale->payment_method) == 'Credit' ? 'selected' : '' }}>Crédit</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="status" style="display: block; margin-bottom: 5px; color: var(--dark);">Statut de la Vente :</label>
                        <select id="status" name="status"
                                style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                            <option value="completed" {{ old('status', $sale->status) == 'completed' ? 'selected' : '' }}>Terminée</option>
                            <option value="pending_credit" {{ old('status', $sale->status) == 'pending_credit' ? 'selected' : '' }}>Crédit</option>
                            {{-- L'annulation est généralement gérée par une action séparée, mais on peut l'afficher --}}
                            <option value="cancelled" {{ old('status', $sale->status) == 'cancelled' ? 'selected' : '' }} disabled>Annulée</option>
                        </select>
                    </div>
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Mettre à jour la Vente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const paidAmountInput = document.getElementById('paid_amount');
        const changeAmountDisplay = document.getElementById('change_amount_display');
        const formChangeAmountInput = document.getElementById('form_change_amount');
        const totalAmount = parseFloat(document.querySelector('input[name="total_amount"]').value); // Récupérer le total actuel

        const customerTypeSelect = document.getElementById('customer_type');
        const existingCustomerFields = document.getElementById('existing_customer_fields');
        const newCustomerFields = document.getElementById('new_customer_fields');
        const customerIdSelect = document.getElementById('customer_id');
        const newClientNameInput = document.getElementById('client_name_new');
        const newClientPhoneInput = document.getElementById('client_phone_new');

        // Fonction pour mettre à jour la monnaie à rendre
        function updateChangeAmount() {
            const paidAmount = parseFloat(paidAmountInput.value) || 0;
            const change = paidAmount - totalAmount;
            changeAmountDisplay.textContent = Math.max(0, change).toLocaleString('fr-FR') + ' XOF';
            formChangeAmountInput.value = Math.max(0, change);
        }

        // Gérer l'affichage des champs client
        function toggleCustomerFields() {
            if (customerTypeSelect.value === 'existing') {
                existingCustomerFields.style.display = 'block';
                newCustomerFields.style.display = 'none';
                newClientNameInput.value = ''; // Vider les champs du nouveau client
                newClientPhoneInput.value = '';
                customerIdSelect.setAttribute('required', 'required');
                newClientNameInput.removeAttribute('required');
                newClientPhoneInput.removeAttribute('required');
            } else {
                existingCustomerFields.style.display = 'none';
                newCustomerFields.style.display = 'block';
                customerIdSelect.value = ''; // Vider la sélection du client existant
                customerIdSelect.removeAttribute('required');
                newClientNameInput.setAttribute('required', 'required');
                newClientPhoneInput.setAttribute('required', 'required');
            }
        }

        // Écouteur pour le montant payé
        paidAmountInput.addEventListener('input', updateChangeAmount);

        // Écouteur pour le type de client
        customerTypeSelect.addEventListener('change', toggleCustomerFields);

        // Initialiser l'affichage des champs client et la monnaie à rendre au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomerFields();
            updateChangeAmount();
        });
    </script>
@endsection
