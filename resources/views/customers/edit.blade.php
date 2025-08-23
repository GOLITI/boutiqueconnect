@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Modifier le Client</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('customers.index') }}">Clients</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('customers.edit', $customer->id) }}">Modifier</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Modifier : {{ $customer->name }}</h3>
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

            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Indique que c'est une requête PUT pour la mise à jour --}}

                <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Nom du Client :</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="phone_number" style="display: block; margin-bottom: 5px; color: var(--dark);">Téléphone (facultatif) :</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px; color: var(--dark);">Email (facultatif) :</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                           style="width: 100%; padding: 10px; border: 19px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="address" style="display: block; margin-bottom: 5px; color: var(--dark);">Adresse (facultatif) :</label>
                    <textarea id="address" name="address" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">{{ old('address', $customer->address) }}</textarea>
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Mettre à jour le Client
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
