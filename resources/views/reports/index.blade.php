@extends('layouts.app')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Rapports</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('reports.index') }}">Rapports</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Types de Rapports Disponibles</h3>
            </div>

            <div style="padding: 20px;">
                <ul style="list-style: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    <li style="background-color: var(--grey); border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class='bx bxs-calendar' style="font-size: 3em; color: var(--blue); margin-bottom: 10px;"></i>
                        <h4 style="margin-bottom: 10px; color: var(--dark);">Rapport des Ventes Journalières</h4>
                        <p style="margin-bottom: 15px; color: var(--dark-grey);">Aperçu des ventes pour une journée spécifique.</p>
                        <a href="{{ route('reports.daily_sales') }}" class="btn-download" style="background-color: var(--blue); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                            Voir le Rapport
                        </a>
                    </li>
                    <li style="background-color: var(--grey); border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class='bx  bx-bar-chart-big' style="font-size: 3em; color: var(--blue); margin-bottom: 10px;"></i>
                        <h4 style="margin-bottom: 10px; color: var(--dark);">Rapport des Ventes Périodiques</h4>
                        <p style="margin-bottom: 15px; color: var(--dark-grey);">Tendances des ventes sur des périodes définies (semaine, mois).</p>
                        <a href="{{ route('reports.periodic_sales') }}" class="btn-download" style="background-color: var(--orange); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                            Voir le Rapport
                        </a>
                    </li>
                    <li style="background-color: var(--grey); border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class='bx bxs-crown' style="font-size: 3em; color: var(--yellow); margin-bottom: 10px;"></i>
                        <h4 style="margin-bottom: 10px; color: var(--dark);">Produits les Plus Vendus</h4>
                        <p style="margin-bottom: 15px; color: var(--dark-grey);">Classement des produits les plus performants.</p>
                        <a href="{{ route('reports.top_selling_products') }}" class="btn-download" style="background-color: var(--yellow); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                            Voir le Rapport
                        </a>
                    </li>
                    <li style="background-color: var(--grey); border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class='bx bxs-box' style="font-size: 3em; color: var(--red); margin-bottom: 10px;"></i>
                        <h4 style="margin-bottom: 10px; color: var(--dark);">Rapport de Stock</h4>
                        <p style="margin-bottom: 15px; color: var(--dark-grey);">Valeur du stock et alertes sur les produits.</p>
                        <a href="{{ route('reports.stock_report') }}" class="btn-download" style="background-color: var(--red); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                            Voir le Rapport
                        </a>
                    </li>
                    <li style="background-color: var(--grey); border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class='bx bxs-wallet' style="font-size: 3em; color: var(--green); margin-bottom: 10px;"></i>
                        <h4 style="margin-bottom: 10px; color: var(--dark);">Rapport des Crédits</h4>
                        <p style="margin-bottom: 15px; color: var(--dark-grey);">Vue d'ensemble des dettes des clients.</p>
                        <a href="{{ route('reports.credits_report') }}" class="btn-download" style="background-color: var(--green); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                            Voir le Rapport
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
