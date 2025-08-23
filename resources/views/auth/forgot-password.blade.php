<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - BoutiqueConnect CI</title>
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
        .auth-container input[type="email"] {
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
        .auth-container .links {
            margin-top: 20px;
        }
        .auth-container .links a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 500;
        }
        .auth-container .links a:hover {
            text-decoration: underline;
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
        <h2>Mot de passe oublié ?</h2>
        <p style="margin-bottom: 20px; color: var(--dark-grey);">Entrez votre adresse e-mail pour recevoir un lien de réinitialisation de mot de passe.</p>

        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div>
                <input type="submit" value="Envoyer le lien de réinitialisation">
            </div>
        </form>

        <div class="links">
            <a href="{{ route('login') }}">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
