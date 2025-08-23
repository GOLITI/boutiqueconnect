@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Crédits / Dettes</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('credits.index') }}">Crédits</a></li>
            </ul>
        </div>
        <a href="{{ route('credits.create') }}" class="btn-download" style="background-color: var(--blue);">
            <i class='bx bx-plus' ></i>
            <span class="text">Ajouter un Crédit</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Crédits en Cours</h3>
                <form action="{{ route('credits.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <select name="customer_id"
                            style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="">Tous les clients</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status"
                            style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                        <option value="">Tous les statuts</option>
                        <option value="outstanding" {{ request('status') == 'outstanding' ? 'selected' : '' }}>En Attente</option>
                        <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partiellement Payé</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>En Retard</option>
                    </select>
                    <input type="date" name="due_date_before" value="{{ request('due_date_before') }}" placeholder="Échéance avant..."
                           style="padding: 8px 12px; border: 1px solid var(--grey); border-radius: 8px; background-color: var(--grey); color: var(--dark);">
                    <button type="submit" class="search-btn" style="background-color: var(--blue); color: white; padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer;">
                        <i class='bx bx-filter'></i> Filtrer
                    </button>
                    <a href="{{ route('credits.index') }}" class="btn-download" style="background-color: var(--dark-grey); color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none;">
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
                        <th>ID Crédit</th>
                        <th>Client</th>
                        <th>Montant Initial</th>
                        <th>Montant Payé</th>
                        <th>Montant Restant</th>
                        <th>Date Échéance</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($credits as $credit)
                        <tr class="{{ $credit->status === 'overdue' ? 'overdue-row' : '' }} {{ $credit->status === 'paid' ? 'paid-row' : '' }}">
                            <td>#{{ $credit->id }}</td>
                            <td>{{ $credit->customer->name }}</td>
                            <td>{{ number_format($credit->amount, 0, ',', '.') }} XOF</td>
                            <td>{{ number_format($credit->amount_paid, 0, ',', '.') }} XOF</td>
                            <td>{{ number_format($credit->amount - $credit->amount_paid, 0, ',', '.') }} XOF</td>
                            <td>{{ $credit->due_date ? $credit->due_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <span class="status {{ $credit->status === 'paid' ? 'completed' : ($credit->status === 'partially_paid' ? 'process' : ($credit->status === 'overdue' ? 'pending' : 'default')) }}">
                                    @if ($credit->status === 'paid') Payé
                                    @elseif ($credit->status === 'partially_paid') Partiellement Payé
                                    @elseif ($credit->status === 'overdue') En Retard
                                    @else En Attente
                                    @endif
                                </span>
                            </td>
                            <td>
                                {{-- Bouton Voir Détails --}}
                                <a href="{{ route('credits.show', $credit->id) }}" style="color: var(--blue); margin-right: 10px;" title="Voir détails"><i class='bx bx-info-circle'></i></a>

                                {{-- Bouton Enregistrer Paiement (si pas entièrement payé) --}}
                                @if ($credit->status !== 'paid')
                                    <a href="{{ route('credits.show_record_payment', $credit->id) }}" style="color: var(--green); margin-right: 10px;" title="Enregistrer un paiement"><i class='bx bx-dollar-circle'></i></a>
                                @endif

                                {{-- Bouton Modifier --}}
                                <a href="{{ route('credits.edit', $credit->id) }}" style="color: var(--orange); margin-right: 10px;" title="Modifier le crédit"><i class='bx bx-edit'></i></a>

                                {{-- Formulaire de suppression --}}
                                <form action="{{ route('credits.destroy', $credit->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: var(--red); cursor: pointer; padding: 0;"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce crédit ? Cela affectera la dette totale du client.');"
                                            title="Supprimer le crédit">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px;">Aucun crédit trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $credits->links() }}
            </div>
        </div>
    </div>

    <style>
        .overdue-row {
            background-color: var(--light-orange); /* Rouge clair pour les crédits en retard */
        }
        .paid-row {
            background-color: var(--light-blue); /* Bleu clair pour les crédits payés */
            opacity: 0.8;
        }
        /* Mettre à jour les couleurs des statuts si nécessaire */
        .status.default { /* Pour 'outstanding' */
            background-color: var(--dark-grey);
        }
        .status.process { /* Pour 'partially_paid' */
            background-color: var(--yellow);
        }
        .status.pending { /* Pour 'overdue' */
            background-color: var(--red);
        }
        .status.completed { /* Pour 'paid' */
            background-color: var(--blue);
        }
    </style>
@endsection
