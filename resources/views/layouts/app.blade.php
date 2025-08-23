<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">

	<title>BoutiqueConnect CI - Dashboard</title>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-store'></i>
			<span class="text">BoutiqueConnect CI</span>
		</a>
		<ul class="side-menu top">
			<li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}">
				<a href="{{ route('dashboard') }}">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="{{ Request::routeIs('products.index') || Request::routeIs('products.create') || Request::routeIs('products.edit') ? 'active' : '' }}">
				<a href="{{ route('products.index') }}">
					<i class='bx bxs-box' ></i>
					<span class="text">Gestion Produits</span>
				</a>
			</li>
			<li class="{{ Request::routeIs('sales.create') ? 'active' : '' }}">
				<a href="{{ route('sales.create') }}">
					<i class='bx bxs-cart' ></i>
					<span class="text">Nouvelle Vente</span>
				</a>
			</li>
			<li class="{{ Request::routeIs('sales.index') || Request::routeIs('sales.show') || Request::routeIs('sales.edit') ? 'active' : '' }}">
				<a href="{{ route('sales.index') }}">
					<i class='bx bxs-receipt' ></i>
					<span class="text">Historique Ventes</span>
				</a>
			</li>
			<li class="{{ Request::routeIs('customers.index') || Request::routeIs('customers.create') || Request::routeIs('customers.edit') || Request::routeIs('customers.show') ? 'active' : '' }}">
				<a href="{{ route('customers.index') }}">
					<i class='bx bxs-group' ></i>
					<span class="text">Gestion Clients</span>
				</a>
			</li>
			<li class="{{ Request::routeIs('credits.index') || Request::routeIs('credits.create') || Request::routeIs('credits.show') || Request::routeIs('credits.edit') || Request::routeIs('credits.show_record_payment') ? 'active' : '' }}">
				<a href="{{ route('credits.index') }}">
					<i class='bx bxs-wallet' ></i>
					<span class="text">Gestion Crédits</span>
				</a>
			</li>
			<li class="{{ Request::routeIs('reports.index') || Request::routeIs('reports.daily_sales') || Request::routeIs('reports.periodic_sales') || Request::routeIs('reports.top_selling_products') || Request::routeIs('reports.stock_report') || Request::routeIs('reports.credits_report') ? 'active' : '' }}">
				<a href="{{ route('reports.index') }}">
					<i class='bx bxs-report' ></i>
					<span class="text">Rapports</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li class="{{ Request::routeIs('profile.show') || Request::routeIs('profile.edit') ? 'active' : '' }}">
				<a href="{{ route('profile.show') }}">
					<i class='bx bxs-cog' ></i>
					<span class="text">Profil</span>
				</a>
			</li>
			<li>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

				<a href="#" class="logout" onclick="event.preventDefault(); console.log('Tentative de déconnexion. Form action:', document.getElementById('logout-form').action); document.getElementById('logout-form').submit();">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Déconnexion</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Vue Rapide</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Rechercher un produit...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="{{ route('notifications.index') }}" class="notification"> {{-- Lien vers la page de notifications --}}
				<i class='bx bxs-bell' ></i>
				<span class="num">{{ $unreadNotificationsCount ?? 0 }}</span> {{-- Affiche le nombre de notifications non lues --}}
			</a>
			<a href="{{ route('profile.show') }}" class="profile">
                @auth
				    <img src="{{ Auth::user()->profile_photo_url }}" alt="profile">
                @else
                    <img src="{{ asset('img/people.png') }}" alt="profile">
                @endauth
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN CONTENT FOR EACH PAGE -->
		<main>
            @yield('content')
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->


	<script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
