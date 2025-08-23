@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Mon Profil</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('profile.show') }}">Profil</a></li>
            </ul>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn-download" style="background-color: var(--orange);">
            <i class='bx bxs-edit' ></i>
            <span class="text">Modifier le Profil</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Informations du Compte</h3>
            </div>

            <div style="padding: 20px; text-align: center;">
                <img src="{{ $user->profile_photo_url }}" alt="Photo de profil"
                     style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--blue); box-shadow: 0 6px 12px rgba(0,0,0,0.2); margin-bottom: 20px;">
            </div>

            <div style="padding: 20px;">
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Nom :</strong> {{ $user->name }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Email :</strong> {{ $user->email }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Numéro de téléphone :</strong> {{ $user->phone_number }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Adresse :</strong> {{ $user->address }}
                </p>
                <p style="margin-bottom: 10px; font-size: 1.1em; color: var(--dark);">
                    <strong>Membre depuis :</strong> {{ $user->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
@endsection
