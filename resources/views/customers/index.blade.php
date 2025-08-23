@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Clients</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('customers.index') }}">Clients</a></li>
            </ul>
        </div>
        <a href="{{ route('customers.create') }}" class="btn-download" style="background-color: var(--blue);">
            <i class='bx bx-plus' ></i>
            <span class="text">Ajouter un Client</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Liste des Clients</h3>
                <form action="{{ route('customers.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
                    <div class="form-input" style="margin-right: 10px;">
                        <input type="search" name="search" placeholder="Rechercher par nom, tél., email..." value="{{ request('search') }}"
                               style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                    </div>
                    <button type="submit" class="search-btn" style="background-color: var(--blue); color: white; padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer;">
                        <i class='bx bx-search'></i>
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn-download" style="background-color: var(--dark-grey); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none;">
                        <i class='bx bx-reset'></i> Réinitialiser
                    </a>
                </form>
            </div>

            @if (session('success'))
                <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Crédit/Dette</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone_number ?: 'N/A' }}</td>
                            <td>{{ $customer->email ?: 'N/A' }}</td>
                            <td>{{ $customer->address ?: 'N/A' }}</td>
                            <td>
                                <span class="status {{ $customer->total_credit_debt > 0 ? 'process' : ($customer->total_credit_debt < 0 ? 'pending' : 'completed') }}">
                                    {{ number_format($customer->total_credit_debt, 0, ',', '.') }} XOF
                                </span>
                            </td>
                            <td>
                                {{-- Bouton Voir Historique --}}
                                <a href="{{ route('customers.show', $customer->id) }}" style="color: var(--blue); margin-right: 10px;" title="Voir historique d'achats"><i class='bx bx-history'></i></a>

                                {{-- Bouton Modifier --}}
                                <a href="{{ route('customers.edit', $customer->id) }}" style="color: var(--orange); margin-right: 10px;" title="Modifier le client"><i class='bx bx-edit'></i></a>

                                {{-- Formulaire de suppression --}}
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: var(--red); cursor: pointer; padding: 0;"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cela pourrait affecter les ventes et crédits liés.');"
                                            title="Supprimer le client">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">Aucun client trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
