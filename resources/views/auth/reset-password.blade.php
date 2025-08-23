<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - BoutiqueConnect CI</title>
    {{-- Assurez-vous que le chemin vers votre fichier CSS est correct --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Réutiliser les styles de auth-container de login.blade.php / register.blade.php */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--grey);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .auth-container {
            background-color: var(--light);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .auth-container h2 {
            margin-bottom: 20px;
            color: var(--dark);
        }
        .auth-container form div {
            margin-bottom: 15px;
            text-align: left;
        }
        .auth-container label {
            display: block;
            margin-bottom: 5px;
            color: var(--dark);
            font-weight: 500;
        }
        .auth-container input[type="email"],
        .auth-container input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--grey);
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            color: var(--dark);
            background-color: var(--grey);
        }
        .auth-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: var(--blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .auth-container input[type="submit"]:hover {
            background-color: #307cd6;
        }
        .auth-container .error-message, .auth-container .success-message {
            font-size: 14px;
            margin-top: 5px;
            text-align: left;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .auth-container .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .auth-container .success-message {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .auth-container ul.errors {
            list-style: none;
            padding: 0;
            margin-bottom: 15px;
            color: var(--red);
            font-size: 14px;
            text-align: left;
        }
        .auth-container ul.errors li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Réinitialiser le mot de passe</h2>

        {{-- Affiche les messages de session (par exemple, succès) --}}
        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        {{-- Affiche les erreurs de validation --}}
        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Le champ caché pour le jeton de réinitialisation --}}
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>
            </div>

            <div>
                <label for="password">Nouveau mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="password_confirmation">Confirmer le nouveau mot de passe :</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div>
                <input type="submit" value="Réinitialiser le mot de passe">
            </div>
        </form>
    </div>
</body>
</html>
