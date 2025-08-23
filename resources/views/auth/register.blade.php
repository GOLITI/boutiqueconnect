<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire - BoutiqueConnect CI</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="auth-container">
        <h2>S'inscrire</h2>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf {{-- Jeton CSRF pour la sécurité --}}

            <div>
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            
            <div>
                <label for="phone_number">Numéro de téléphone :</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
            </div>

           
            <div>
                <label for="address">Adresse :</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" required>
            </div>

            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="password_confirmation">Confirmer le mot de passe :</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div>
                <input type="submit" value="S'inscrire">
            </div>
        </form>

        <div class="links">
            Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
        </div>
    </div>
</body>
</html>
