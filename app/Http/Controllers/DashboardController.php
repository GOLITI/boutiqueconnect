<?php

namespace App\Http\Controllers;

use App\Models\User; // Assurez-vous d'importer le modèle User
use App\Models\Sale; // Importer le modèle Sale
use App\Models\Customer; // Importer le modèle Customer
use App\Models\Product; // Importer le modèle Product
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Importer Carbon pour la manipulation des dates
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function home(): View{
        return view('layouts.home');
    }
    
    /**
     * Affiche le tableau de bord principal.
     * Accessible uniquement par les utilisateurs authentifiés.
     */
    public function index()
    {
        /** @var App\Models\User $user */
        $user = Auth::user(); // Récupérer l'utilisateur actuellement connecté

        // 1. Calculer les ventes du jour
        $todaySalesAmount = Sale::where('user_id', $user->id)
                                ->whereDate('sale_date', Carbon::today())
                                ->sum('total_amount');

        // 2. Compter le nombre total de clients enregistrés par cet utilisateur
        $totalCustomers = Customer::where('user_id', $user->id)->count();

        // 3. Compter les produits en stock bas
        // Un produit est en stock bas si sa quantité en stock est inférieure ou égale à son seuil d'alerte.
        $lowStockProductsCount = Product::where('user_id', $user->id)
                                        ->whereColumn('stock_quantity', '<=', 'min_stock_alert')
                                        ->count();

        // 4. Récupérer les ventes récentes (par exemple, les 5 dernières)
        $recentSales = Sale::where('user_id', $user->id)
                           ->with('customer') // Charger la relation client pour afficher le nom du client
                           ->orderByDesc('sale_date')
                           ->limit(5)
                           ->get();

        // 5. Récupérer la liste des produits en stock bas pour la section d'alerte
        $lowStockProductsList = Product::where('user_id', $user->id)
                                       ->whereColumn('stock_quantity', '<=', 'min_stock_alert')
                                       ->get();

        // Retourner la vue du tableau de bord avec toutes les données dynamiques
        return view('layouts.dashboard.index', compact(
            'user',
            'todaySalesAmount',
            'totalCustomers',
            'lowStockProductsCount',
            'recentSales',
            'lowStockProductsList'
        ));
    }
}
