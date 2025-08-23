<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - BoutiqueConnect CI</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

</head>
<body>
    <!-- Animated background particles -->
    <div class="particles" id="particles"></div>

    <header class="home-header">
        <a href="{{ route('home') }}" class="brand">
            <i class='bx bxs-store'></i>
            <span class="text">BoutiqueConnect CI</span>
        </a>
        <div class="auth-links">
            <a href="{{ route('login') }}">Se connecter</a>
            <a href="{{ route('register') }}" class="button">S'inscrire</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">BoutiqueConnect CI</h1>
        <p class="hero-subtitle">La solution moderne pour gérer votre commerce en Côte d'Ivoire</p>
        <a href="#" class="hero-cta">Commencer maintenant</a>
    </section>

    <main class="home-content">
        <!-- Features Section -->
        <div class="home-section scroll-reveal">
            <div class="home-card">
                <i class='bx bxs-store icon'></i>
                <h3>Gérez votre Boutique Facilement</h3>
                <p>Simplifiez la gestion de vos stocks, ventes et clients avec notre application intuitive conçue pour les commerçants ivoiriens.</p>
            </div>
            <div class="home-card">
                <i class='bx bxs-dollar-circle icon'></i>
                <h3>Suivi des Ventes et Crédits</h3>
                <p>Gardez un œil sur vos revenus et gérez les dettes de vos clients habitués en toute simplicité avec des rapports détaillés.</p>
            </div>
            <div class="home-card">
                <i class='bx bxs-report icon'></i>
                <h3>Rapports Clairs et Précis</h3>
                <p>Obtenez des aperçus détaillés de votre activité pour prendre les meilleures décisions et développer votre business.</p>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section scroll-reveal">
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Boutiques connectées</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">1M+</div>
                <div class="stat-label">Transactions traitées</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">99%</div>
                <div class="stat-label">Satisfaction client</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support disponible</div>
            </div>
        </div>

        <!-- About Section -->
        <div class="home-section full-width scroll-reveal">
            <div class="about-us-card">
                <h3>À Propos de BoutiqueConnect CI</h3>
                <p>BoutiqueConnect CI est une application web révolutionnaire conçue spécifiquement pour les petits commerçants en Côte d'Ivoire. Notre mission est de moderniser la gestion de vos affaires en remplaçant les cahiers traditionnels par une solution numérique simple, accessible et terriblement efficace.</p>
                <p>Que vous soyez propriétaire d'un maquis, d'un salon de coiffure, d'une boutique de quartier ou de tout autre commerce, BoutiqueConnect CI est conçu pour vous accompagner dans l'optimisation de vos opérations quotidiennes et le développement durable de votre activité.</p>
                <p>Nous nous engageons à fournir une interface utilisateur ultra-intuitive, parfaitement adaptée aux smartphones, pour que même les utilisateurs les moins familiers avec les outils numériques puissent en tirer le maximum de profit dès le premier jour.</p>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="home-section scroll-reveal">
            <div class="contact-card">
                <h3>Contactez-nous</h3>
                <p>Des questions ? Une suggestion ? Notre équipe est là pour vous accompagner !</p>
                <p><i class='bx bxs-envelope'></i> contact@boutiqueconnectci.com</p>
                <p><i class='bx bxs-phone'></i> +225 07 00 00 00 00</p>
                <p><i class='bx bxs-location-plus'></i> Abidjan, Côte d'Ivoire</p>
            </div>
        </div>
    </main>

    <footer class="home-footer">
        <p>&copy; 2025 BoutiqueConnect CI. Tous droits réservés. | Fait avec ❤️ en Côte d'Ivoire</p>
    </footer>

    
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>