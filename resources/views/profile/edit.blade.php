@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Paramètres du Profil</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a href="{{ route('profile.show') }}">Profil</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('profile.show') }}">Annuler</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Modifier mes informations</h3>
            </div>

            @if (session('success'))
                <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- IMPORTANT: Ajoutez enctype="multipart/form-data" pour l'upload de fichiers --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 25px; text-align: center;">
                    <label for="profile_photo" style="display: block; margin-bottom: 10px; color: var(--dark); font-weight: 600;">
                        Photo de Profil :
                    </label>
                    <div style="position: relative; display: inline-block; cursor: pointer;">
                        <img src="{{ $user->profile_photo_url }}" alt="Photo de profil"
                             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--blue); box-shadow: 0 4px 8px rgba(0,0,0,0.15);">
                        <div style="position: absolute; bottom: 0; right: 0; background-color: var(--blue); color: white; border-radius: 50%; padding: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: background-color 0.3s ease;">
                            <i class='bx bxs-camera' style="font-size: 20px;"></i>
                        </div>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    </div>
                    <p style="font-size: 0.8em; color: var(--dark-grey); margin-top: 10px;">Cliquez sur l'image pour changer votre photo de profil.</p>
                </div>


                <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Nom :</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px; color: var(--dark);">Email :</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="phone_number" style="display: block; margin-bottom: 5px; color: var(--dark);">Numéro de téléphone :</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="address" style="display: block; margin-bottom: 5px; color: var(--dark);">Adresse :</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" required
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <h4 style="margin-top: 30px; margin-bottom: 15px; color: var(--dark);">Changer le mot de passe (facultatif)</h4>

                <div style="margin-bottom: 15px;">
                    <label for="current_password" style="display: block; margin-bottom: 5px; color: var(--dark);">Mot de passe actuel :</label>
                    <input type="password" id="current_password" name="current_password"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="password" style="display: block; margin-bottom: 5px; color: var(--dark);">Nouveau mot de passe :</label>
                    <input type="password" id="password" name="password"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="password_confirmation" style="display: block; margin-bottom: 5px; color: var(--dark);">Confirmer le nouveau mot de passe :</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           style="width: 100%; padding: 10px; border: 1px solid var(--grey); border-radius: 8px; box-sizing: border-box; font-size: 16px; color: var(--dark); background-color: var(--grey);">
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: var(--blue); color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">
                        Mettre à jour le profil
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
