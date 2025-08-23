@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Nouvelle Vente</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('sales.index') }}">Ventes</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('sales.create') }}">Nouvelle Vente</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Enregistrer une Vente</h3>
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

            <form id="saleForm" action="{{ route('sales.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 20px; border: 1px solid var(--grey); padding: 15px; border-radius: 10px;">
                    <h4 style="margin-bottom: 15px; color: var(--dark);">Informations Client</h4>
                    <div style="margin-bottom: 15px;">
                        <label for="customer_type" style="display: block; margin-bottom: 5px; color: var(--dark);">Type de Client :</label>
                        <select id="customer_type" name="customer_type"
                                style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                            <option value="existing" {{ old('customer_type') == 'existing' ? 'selected' : '' }}>Client existant</option>
                            <option value="new_temp" {{ old('customer_type') == 'new_temp' ? 'selected' : '' }}>Nouveau client (temporaire)</option>
                        </select>
                    </div>

                    <div id="existing_customer_fields" style="margin-bottom: 15px; {{ old('customer_type', 'existing') == 'new_temp' ? 'display: none;' : '' }}">
                        <label for="customer_id" style="display: block; margin-bottom: 5px; color: var(--dark);">Sélectionner un client :</label>
                        <select id="customer_id" name="customer_id"
                                style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                            <option value="">-- Aucun client --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="new_customer_fields" style="margin-bottom: 15px; {{ old('customer_type', 'existing') == 'existing' ? 'display: none;' : '' }}">
                        <div style="margin-bottom: 15px;">
                            <label for="client_name_new" style="display: block; margin-bottom: 5px; color: var(--dark);">Nom du nouveau client :</label>
                            <input type="text" id="client_name_new" name="client_name_new" value="{{ old('client_name_new') }}"
                                   style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                        </div>
                        <div>
                            <label for="client_phone_new" style="display: block; margin-bottom: 5px; color: var(--dark);">Téléphone du nouveau client :</label>
                            <input type="text" id="client_phone_new" name="client_phone_new" value="{{ old('client_phone_new') }}"
                                   style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px; border: 1px solid var(--grey); padding: 15px; border-radius: 10px;">
                    <h4 style="margin-bottom: 15px; color: var(--dark);">Ajouter des Produits au Panier</h4>
                    <div style="margin-bottom: 15px;">
                        <label for="product_search" style="display: block; margin-bottom: 5px; color: var(--dark);">Rechercher un produit :</label>
                        <input type="text" id="product_search" placeholder="Nom ou code-barres du produit..."
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                        <div id="search_results" style="border: 1px solid var(--grey); border-top: none; max-height: 200px; overflow-y: auto; background-color: var(--light); display: none;"></div>
                    </div>
                    <button type="button" id="add_product_manual" style="background-color: var(--orange); color: white; padding: 8px 15px; border-radius: 8px; border: none; cursor: pointer;">
                        Ajouter manuellement (si non trouvé)
                    </button>
                </div>

                <div style="margin-bottom: 20px; border: 1px solid var(--grey); padding: 15px; border-radius: 10px;">
                    <h4 style="margin-bottom: 15px; color: var(--dark);">Panier (<span id="cart_count">0</span> articles)</h4>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <thead>
                            <tr>
                                <th style="text-align: left; padding: 8px 0; border-bottom: 1px solid var(--dark-grey);">Produit</th>
                                <th style="text-align: center; padding: 8px 0; border-bottom: 1px solid var(--dark-grey);">Qté</th>
                                <th style="text-align: right; padding: 8px 0; border-bottom: 1px solid var(--dark-grey);">Prix Unitaire</th>
                                <th style="text-align: right; padding: 8px 0; border-bottom: 1px solid var(--dark-grey);">Sous-total</th>
                                <th style="text-align: center; padding: 8px 0; border-bottom: 1px solid var(--dark-grey);">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cart_items_body">
                            {{-- Les articles du panier seront ajoutés ici par JS --}}
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: var(--dark-grey);">Le panier est vide.</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; padding: 10px 0; font-weight: 600; color: var(--dark);">Total :</td>
                                <td id="cart_total" style="text-align: right; padding: 10px 0; font-weight: 600; color: var(--blue);">0 XOF</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    <input type="hidden" name="total_amount" id="form_total_amount">
                </div>

                <div style="margin-bottom: 20px; border: 1px solid var(--grey); padding: 15px; border-radius: 10px;">
                    <h4 style="margin-bottom: 15px; color: var(--dark);">Paiement</h4>
                    <div style="margin-bottom: 15px;">
                        <label for="payment_method" style="display: block; margin-bottom: 5px; color: var(--dark);">Mode de Paiement :</label>
                        <select id="payment_method" name="payment_method"
                                style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                            <option value="Cash">Cash</option>
                            <option value="Mobile Money">Mobile Money</option>
                            <option value="Credit">Crédit</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="paid_amount" style="display: block; margin-bottom: 5px; color: var(--dark);">Montant Payé (XOF) :</label>
                        <input type="number" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', 0) }}" step="0.01" required
                               style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <p style="color: var(--dark); font-weight: 600;">Monnaie à Rendre : <span id="change_amount_display" style="color: var(--green);">0 XOF</span></p>
                        <input type="hidden" name="change_amount" id="form_change_amount">
                    </div>
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Enregistrer la Vente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Données des produits disponibles (passées du contrôleur)
        const productsData = @json($products);
        const customersData = @json($customers);

        let cart = []; // Le panier
        let currentTotal = 0;

        const productSearchInput = document.getElementById('product_search');
        const searchResultsDiv = document.getElementById('search_results');
        const cartItemsBody = document.getElementById('cart_items_body');
        const cartTotalDisplay = document.getElementById('cart_total');
        const cartCountDisplay = document.getElementById('cart_count');
        const paidAmountInput = document.getElementById('paid_amount');
        const changeAmountDisplay = document.getElementById('change_amount_display');
        const formTotalAmountInput = document.getElementById('form_total_amount');
        const formChangeAmountInput = document.getElementById('form_change_amount');
        const customerTypeSelect = document.getElementById('customer_type');
        const existingCustomerFields = document.getElementById('existing_customer_fields');
        const newCustomerFields = document.getElementById('new_customer_fields');
        const customerIdSelect = document.getElementById('customer_id');
        const newClientNameInput = document.getElementById('client_name_new');
        const newClientPhoneInput = document.getElementById('client_phone_new');

        // Fonction pour mettre à jour l'affichage du panier
        function updateCartDisplay() {
            cartItemsBody.innerHTML = ''; // Vide le contenu actuel du panier
            currentTotal = 0;
            let totalItems = 0;

            if (cart.length === 0) {
                cartItemsBody.innerHTML = `<tr><td colspan="5" style="text-align: center; padding: 20px; color: var(--dark-grey);">Le panier est vide.</td></tr>`;
            } else {
                cart.forEach((item, index) => {
                    const subtotal = item.quantity * item.unit_price;
                    currentTotal += subtotal;
                    totalItems += item.quantity;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td style="padding: 8px 0; border-bottom: 1px solid var(--grey); color: var(--dark);">${item.name}</td>
                        <td style="text-align: center; padding: 8px 0; border-bottom: 1px solid var(--grey);">
                            <input type="number" value="${item.quantity}" min="1" data-index="${index}" class="quantity-input"
                                style="width: 60px; padding: 5px; border: 1px solid var(--grey); border-radius: 5px; text-align: center;">
                        </td>
                        <td style="text-align: right; padding: 8px 0; border-bottom: 1px solid var(--grey); color: var(--dark-grey);">${item.unit_price.toLocaleString('fr-FR')} XOF</td>
                        <td style="text-align: right; padding: 8px 0; border-bottom: 1px solid var(--grey); font-weight: 500; color: var(--dark);">${subtotal.toLocaleString('fr-FR')} XOF</td>
                        <td style="text-align: center; padding: 8px 0; border-bottom: 1px solid var(--grey);">
                            <button type="button" data-index="${index}" class="remove-item-btn" style="background: none; border: none; color: var(--red); cursor: pointer;">
                                <i class='bx bx-trash'></i>
                            </button>
                        </td>
                    `;
                    cartItemsBody.appendChild(row);
                });
            }

            cartTotalDisplay.textContent = currentTotal.toLocaleString('fr-FR') + ' XOF';
            cartCountDisplay.textContent = totalItems;
            formTotalAmountInput.value = currentTotal; // Met à jour le champ caché pour la soumission du formulaire

            // Mettre à jour la monnaie à rendre
            updateChangeAmount();

            // Attacher les écouteurs d'événements aux nouveaux éléments
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', handleQuantityChange);
            });
            document.querySelectorAll('.remove-item-btn').forEach(button => {
                button.addEventListener('click', handleRemoveItem);
            });
        }

        // Fonction pour ajouter un produit au panier
        function addProductToCart(product, quantity = 1) {
            const existingItemIndex = cart.findIndex(item => item.product_id === product.id);

            if (existingItemIndex > -1) {
                // Si le produit est déjà dans le panier, augmenter la quantité
                if (cart[existingItemIndex].quantity + quantity <= product.stock_quantity) {
                    cart[existingItemIndex].quantity += quantity;
                } else {
                    alert(`Quantité insuffisante en stock pour ${product.name}. Stock disponible: ${product.stock_quantity}`);
                }
            } else {
                // Sinon, ajouter le nouveau produit
                if (quantity <= product.stock_quantity) {
                    cart.push({
                        product_id: product.id,
                        name: product.name,
                        unit_price: parseFloat(product.sale_price),
                        quantity: quantity,
                        stock_available: product.stock_quantity // Pour référence
                    });
                } else {
                    alert(`Quantité insuffisante en stock pour ${product.name}. Stock disponible: ${product.stock_quantity}`);
                }
            }
            updateCartDisplay();
            productSearchInput.value = ''; // Effacer le champ de recherche
            searchResultsDiv.style.display = 'none'; // Cacher les résultats
        }

        // Gérer le changement de quantité dans le panier
        function handleQuantityChange(event) {
            const index = event.target.dataset.index;
            let newQuantity = parseInt(event.target.value);

            if (isNaN(newQuantity) || newQuantity < 1) {
                newQuantity = 1;
                event.target.value = 1;
            }

            const productInCart = cart[index];
            const originalProduct = productsData.find(p => p.id === productInCart.product_id);

            if (originalProduct && newQuantity > originalProduct.stock_quantity) {
                alert(`Quantité maximale en stock pour ${originalProduct.name} est de ${originalProduct.stock_quantity}.`);
                newQuantity = originalProduct.stock_quantity;
                event.target.value = newQuantity;
            }

            cart[index].quantity = newQuantity;
            updateCartDisplay();
        }

        // Gérer la suppression d'un article du panier
        function handleRemoveItem(event) {
            const index = event.target.closest('button').dataset.index;
            cart.splice(index, 1); // Supprimer l'article du tableau
            updateCartDisplay();
        }

        // Mise à jour de la monnaie à rendre
        function updateChangeAmount() {
            const paidAmount = parseFloat(paidAmountInput.value) || 0;
            const change = paidAmount - currentTotal;
            changeAmountDisplay.textContent = Math.max(0, change).toLocaleString('fr-FR') + ' XOF';
            formChangeAmountInput.value = Math.max(0, change); // Met à jour le champ caché
        }

        // Écouteur pour la recherche de produits
        productSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            searchResultsDiv.innerHTML = ''; // Vider les résultats précédents

            if (searchTerm.length > 0) {
                const filteredProducts = productsData.filter(product =>
                    product.name.toLowerCase().includes(searchTerm) ||
                    (product.barcode && product.barcode.toLowerCase().includes(searchTerm))
                );

                if (filteredProducts.length > 0) {
                    filteredProducts.forEach(product => {
                        const resultItem = document.createElement('div');
                        resultItem.style.padding = '10px';
                        resultItem.style.cursor = 'pointer';
                        resultItem.style.borderBottom = '1px solid var(--grey)';
                        resultItem.textContent = `${product.name} (Stock: ${product.stock_quantity}, Prix: ${product.sale_price.toLocaleString('fr-FR')} XOF)`;
                        resultItem.addEventListener('click', () => addProductToCart(product));
                        searchResultsDiv.appendChild(resultItem);
                    });
                    searchResultsDiv.style.display = 'block';
                } else {
                    searchResultsDiv.innerHTML = `<div style="padding: 10px; color: var(--dark-grey);">Aucun produit trouvé.</div>`;
                    searchResultsDiv.style.display = 'block';
                }
            } else {
                searchResultsDiv.style.display = 'none';
            }
        });

        // Cacher les résultats de recherche si on clique en dehors
        document.addEventListener('click', function(event) {
            if (!productSearchInput.contains(event.target) && !searchResultsDiv.contains(event.target)) {
                searchResultsDiv.style.display = 'none';
            }
        });

        // Écouteur pour le montant payé
        paidAmountInput.addEventListener('input', updateChangeAmount);

        // Gérer l'affichage des champs client
        customerTypeSelect.addEventListener('change', function() {
            if (this.value === 'existing') {
                existingCustomerFields.style.display = 'block';
                newCustomerFields.style.display = 'none';
                newClientNameInput.value = ''; // Vider les champs du nouveau client
                newClientPhoneInput.value = '';
                // Rendre la sélection d'un client existant requise, et les champs du nouveau client non requis
                customerIdSelect.setAttribute('required', 'required');
                newClientNameInput.removeAttribute('required');
                newClientPhoneInput.removeAttribute('required');
            } else {
                existingCustomerFields.style.display = 'none';
                newCustomerFields.style.display = 'block';
                customerIdSelect.value = ''; // Vider la sélection du client existant
                // Rendre les champs du nouveau client requis, et la sélection d'un client existant non requise
                newClientNameInput.setAttribute('required', 'required');
                newClientPhoneInput.setAttribute('required', 'required');
                customerIdSelect.removeAttribute('required');
            }
        });

        // Initialiser l'affichage des champs client au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            customerTypeSelect.dispatchEvent(new Event('change')); // Déclenche le changement pour initialiser
            updateCartDisplay(); // Initialiser le panier vide
        });

        // Soumission du formulaire : ajouter les articles du panier en tant que champs cachés
        document.getElementById('saleForm').addEventListener('submit', function(event) {
            // Supprimer les anciens champs cachés d'articles de panier si présents
            document.querySelectorAll('input[name^="cart_items"]').forEach(input => input.remove());

            cart.forEach((item, index) => {
                // Créer des champs cachés pour chaque propriété de l'article du panier
                const productIdInput = document.createElement('input');
                productIdInput.type = 'hidden';
                productIdInput.name = `cart_items[${index}][product_id]`;
                productIdInput.value = item.product_id;
                this.appendChild(productIdInput);

                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = `cart_items[${index}][quantity]`;
                quantityInput.value = item.quantity;
                this.appendChild(quantityInput);

                const unitPriceInput = document.createElement('input');
                unitPriceInput.type = 'hidden';
                unitPriceInput.name = `cart_items[${index}][unit_price]`;
                unitPriceInput.value = item.unit_price;
                this.appendChild(unitPriceInput);
            });

            if (cart.length === 0) {
                alert("Le panier ne peut pas être vide pour enregistrer une vente.");
                event.preventDefault(); // Empêche la soumission du formulaire
            }

            // Gérer la validation côté client pour les champs du client temporaire
            if (customerTypeSelect.value === 'new_temp') {
                if (newClientNameInput.value.trim() === '') {
                    alert("Le nom du nouveau client est requis.");
                    event.preventDefault();
                    newClientNameInput.focus();
                    return;
                }
                if (newClientPhoneInput.value.trim() === '') {
                    alert("Le numéro de téléphone du nouveau client est requis.");
                    event.preventDefault();
                    newClientPhoneInput.focus();
                    return;
                }
            } else if (customerTypeSelect.value === 'existing') {
                 if (customerIdSelect.value === '') {
                    alert("Veuillez sélectionner un client existant.");
                    event.preventDefault();
                    customerIdSelect.focus();
                    return;
                }
            }
        });
    </script>
@endsection
