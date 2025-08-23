<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - BoutiqueConnect CI</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{asset('css/login.css') }}">
    <!-- Boxicons pour les icônes -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="auth-container">
        <h2>Se connecter</h2>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="password-input-container">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <i class='bx bx-show toggle-password' id="togglePassword"></i>
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>

            <div class="forgot-password-link">
                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            </div>

            <div>
                <input type="submit" value="Se connecter">
            </div>
        </form>

        <div class="links">
            Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // Basculer le type de l'input entre 'password' et 'text'
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Basculer l'icône de l'œil
            this.classList.toggle('bx-hide');
            this.classList.toggle('bx-show');
        });
    </script>
</body>
</html>
