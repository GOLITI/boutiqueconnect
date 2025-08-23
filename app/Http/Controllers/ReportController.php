<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer; // N'oubliez pas d'importer le modèle Customer
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// use Symfony\Component\HttpFoundation\StreamedResponse; // Plus nécessaire pour le CSV

// Importer Dompdf
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    /**
     * Affiche la page d'accueil des rapports.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Génère le rapport des ventes journalières.
     */
    public function dailySales(Request $request)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $date = $request->input('date', Carbon::today()->toDateString()); // Date par défaut: aujourd'hui

        $sales = $user->sales()
                      ->whereDate('sale_date', $date)
                      ->with('saleItems.product', 'customer')
                      ->get();

        $totalSalesAmount = $sales->sum('total_amount');
        $numberOfTransactions = $sales->count();
        $salesByPaymentMethod = $sales->groupBy('payment_method')->map(function ($group) {
            return $group->sum('total_amount');
        });

        return view('reports.daily-sales', compact('sales', 'date', 'totalSalesAmount', 'numberOfTransactions', 'salesByPaymentMethod'));
    }

    /**
     * Génère le rapport des ventes hebdomadaires / mensuelles.
     */
    public function periodicSales(Request $request)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Définir les dates par défaut si non fournies
        if (empty($startDate) || empty($endDate)) {
            $startDate = Carbon::now()->startOfWeek()->toDateString();
            $endDate = Carbon::now()->endOfWeek()->toDateString();
        }

        $sales = $user->sales()
                      ->whereBetween('sale_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                      ->with('saleItems.product', 'customer')
                      ->get();

        $totalSalesAmount = $sales->sum('total_amount');
        $numberOfTransactions = $sales->count();

        // Préparer les données de tendance des ventes avec les dates formatées et les comptages
        $salesTrendData = $sales->groupBy(function ($sale) {
            return Carbon::parse($sale->sale_date)->format('Y-m-d'); // Grouper par chaîne Y-m-d
        })->map(function ($group, $dateKey) {
            return [
                'date_formatted' => Carbon::parse($dateKey)->format('d/m/Y'), // Formater pour l'affichage
                'total_amount' => $group->sum('total_amount'),
                'number_of_sales' => $group->count(),
            ];
        })->sortBy(fn($item) => Carbon::createFromFormat('d/m/Y', $item['date_formatted']))->values(); // Trier par date et réinitialiser les clés

        return view('reports.periodic-sales', compact('sales', 'startDate', 'endDate', 'totalSalesAmount', 'numberOfTransactions', 'salesTrendData'));
    }

    /**
     * Génère le rapport des produits les plus vendus.
     */
    public function topSellingProducts(Request $request)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        $topProducts = $user->sales()
                            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                            ->join('products', 'sale_items.product_id', '=', 'products.id')
                            ->whereBetween('sales.sale_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                            ->select(
                                'products.name as product_name',
                                DB::raw('SUM(sale_items.quantity) as total_quantity_sold'),
                                DB::raw('SUM(sale_items.subtotal) as total_revenue')
                            )
                            ->groupBy('products.id', 'products.name')
                            ->orderByDesc('total_quantity_sold') // Ou orderByDesc('total_revenue')
                            ->limit(10) // Top 10 produits
                            ->get();

        return view('reports.top-selling-products', compact('topProducts', 'startDate', 'endDate'));
    }

    /**
     * Génère le rapport de stock.
     */
    public function stockReport()
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $products = $user->products()->orderBy('name')->get();

        $totalStockValuePurchase = $products->sum(function($product) {
            return $product->stock_quantity * $product->purchase_price;
        });
        $totalStockValueSale = $products->sum(function($product) {
            return $product->stock_quantity * $product->sale_price;
        });

        $lowStockProducts = $products->filter(function($product) {
            return $product->stock_quantity <= $product->min_stock_alert;
        });

        return view('reports.stock-report', compact('products', 'totalStockValuePurchase', 'totalStockValueSale', 'lowStockProducts'));
    }

    /**
     * Génère le rapport des crédits/dettes.
     */
    public function creditsReport(Request $request)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $credits = $user->credits()->with('customer')->get();

        $totalOutstandingCredit = $credits->where('status', 'outstanding')->sum('amount');
        $totalPartiallyPaidCredit = $credits->where('status', 'partially_paid')->sum(function($credit) {
            return $credit->amount - $credit->amount_paid;
        });
        $totalOverdueCredit = $credits->where('status', 'overdue')->sum(function($credit) {
            return $credit->amount - $credit->amount_paid;
        });
        $totalCreditPaid = $credits->where('status', 'paid')->sum('amount');
        $overallTotalCreditDebt = $credits->sum(function($credit) {
            return $credit->amount - $credit->amount_paid;
        });


        // Crédits par statut
        $creditsByStatus = $credits->groupBy('status')->map(function ($group) {
            return $group->sum(function($credit) {
                return $credit->amount - $credit->amount_paid; // Solde restant pour chaque statut
            });
        });

        // Crédits par client (top 5 des clients les plus endettés)
        $topIndebtedCustomers = $user->customers()
                                     ->where('total_credit_debt', '>', 0)
                                     ->orderByDesc('total_credit_debt')
                                     ->limit(5)
                                     ->get();

        return view('reports.credits-report', compact(
            'credits',
            'totalOutstandingCredit',
            'totalPartiallyPaidCredit',
            'totalOverdueCredit',
            'totalCreditPaid',
            'overallTotalCreditDebt',
            'creditsByStatus',
            'topIndebtedCustomers'
        ));
    }

    /**
     * Exporte un rapport en PDF.
     * @param string $reportType Le type de rapport à exporter (e.g., 'daily-sales', 'stock-report', 'dashboard-summary')
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request, $reportType)
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        $data = [];
        $viewName = '';
        $filename = $reportType . '-' . Carbon::now()->format('Y-m-d');

        switch ($reportType) {
            case 'daily-sales':
                $date = $request->input('date', Carbon::today()->toDateString());
                $sales = $user->sales()
                              ->whereDate('sale_date', $date)
                              ->with('saleItems.product', 'customer')
                              ->get();
                $totalSalesAmount = $sales->sum('total_amount');
                $numberOfTransactions = $sales->count();
                $salesByPaymentMethod = $sales->groupBy('payment_method')->map(function ($group) {
                    return $group->sum('total_amount');
                });
                $data = compact('sales', 'date', 'totalSalesAmount', 'numberOfTransactions', 'salesByPaymentMethod');
                $viewName = 'reports.pdfs.daily-sales-pdf';
                break;

            case 'periodic-sales':
                $startDate = $request->input('start_date', Carbon::now()->startOfWeek()->toDateString());
                $endDate = $request->input('end_date', Carbon::now()->endOfWeek()->toDateString());
                $sales = $user->sales()
                              ->whereBetween('sale_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                              ->with('saleItems.product', 'customer')
                              ->get();
                $totalSalesAmount = $sales->sum('total_amount');
                $numberOfTransactions = $sales->count();
                // Add salesTrendData for PDF as well
                $salesTrendData = $sales->groupBy(function ($sale) {
                    return Carbon::parse($sale->sale_date)->format('Y-m-d');
                })->map(function ($group, $dateKey) {
                    return [
                        'date_formatted' => Carbon::parse($dateKey)->format('d/m/Y'),
                        'total_amount' => $group->sum('total_amount'),
                        'number_of_sales' => $group->count(),
                    ];
                })->sortBy(fn($item) => Carbon::createFromFormat('d/m/Y', $item['date_formatted']))->values();
                $data = compact('sales', 'startDate', 'endDate', 'totalSalesAmount', 'numberOfTransactions', 'salesTrendData'); // Pass salesTrendData
                $viewName = 'reports.pdfs.periodic-sales-pdf';
                break;

            case 'top-selling-products':
                $startDate = $request->input('start_date', Carbon::now()->subMonth()->toDateString());
                $endDate = $request->input('end_date', Carbon::today()->toDateString());
                $topProducts = $user->sales()
                                    ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                                    ->join('products', 'sale_items.product_id', '=', 'products.id')
                                    ->whereBetween('sales.sale_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                                    ->select(
                                        'products.name as product_name',
                                        DB::raw('SUM(sale_items.quantity) as total_quantity_sold'),
                                        DB::raw('SUM(sale_items.subtotal) as total_revenue')
                                    )
                                    ->groupBy('products.id', 'products.name')
                                    ->orderByDesc('total_quantity_sold')
                                    ->limit(10)
                                    ->get();
                $data = compact('topProducts', 'startDate', 'endDate');
                $viewName = 'reports.pdfs.top-selling-products-pdf';
                break;

            case 'stock-report':
                $products = $user->products()->orderBy('name')->get();
                $totalStockValuePurchase = $products->sum(function($product) {
                    return $product->stock_quantity * $product->purchase_price;
                });
                $totalStockValueSale = $products->sum(function($product) {
                    return $product->stock_quantity * $product->sale_price;
                });
                $lowStockProducts = $products->filter(function($product) {
                    return $product->stock_quantity <= $product->min_stock_alert;
                });
                $data = compact('products', 'totalStockValuePurchase', 'totalStockValueSale', 'lowStockProducts');
                $viewName = 'reports.pdfs.stock-report-pdf';
                break;

            case 'credits-report':
                $credits = $user->credits()->with('customer')->get();
                $totalOutstandingCredit = $credits->where('status', 'outstanding')->sum('amount');
                $totalPartiallyPaidCredit = $credits->where('status', 'partially_paid')->sum(function($credit) {
                    return $credit->amount - $credit->amount_paid;
                });
                $totalOverdueCredit = $credits->where('status', 'overdue')->sum(function($credit) {
                    return $credit->amount - $credit->amount_paid;
                });
                $totalCreditPaid = $credits->where('status', 'paid')->sum('amount');
                $overallTotalCreditDebt = $credits->sum(function($credit) {
                    return $credit->amount - $credit->amount_paid;
                });
                $creditsByStatus = $credits->groupBy('status')->map(function ($group) {
                    return $group->sum(function($credit) {
                        return $credit->amount - $credit->amount_paid;
                    });
                });
                $topIndebtedCustomers = $user->customers()
                                             ->where('total_credit_debt', '>', 0)
                                             ->orderByDesc('total_credit_debt')
                                             ->limit(5)
                                             ->get();
                $data = compact(
                    'credits',
                    'totalOutstandingCredit',
                    'totalPartiallyPaidCredit',
                    'totalOverdueCredit',
                    'totalCreditPaid',
                    'overallTotalCreditDebt',
                    'creditsByStatus',
                    'topIndebtedCustomers'
                );
                $viewName = 'reports.pdfs.credits-report-pdf';
                break;

            case 'dashboard-summary': // Nouveau cas pour le rapport du tableau de bord
                // Collecte les mêmes données que DashboardController::index()
                $todaySalesAmount = Sale::where('user_id', $user->id)
                                        ->whereDate('sale_date', Carbon::today())
                                        ->sum('total_amount');
                $totalCustomers = Customer::where('user_id', $user->id)->count();
                $lowStockProductsCount = Product::where('user_id', $user->id)
                                                ->whereColumn('stock_quantity', '<=', 'min_stock_alert')
                                                ->count();
                $recentSales = Sale::where('user_id', $user->id)
                                   ->with('customer')
                                   ->orderByDesc('sale_date')
                                   ->limit(5)
                                   ->get();
                $lowStockProductsList = Product::where('user_id', $user->id)
                                               ->whereColumn('stock_quantity', '<=', 'min_stock_alert')
                                               ->get();

                $data = compact(
                    'todaySalesAmount',
                    'totalCustomers',
                    'lowStockProductsCount',
                    'recentSales',
                    'lowStockProductsList'
                );
                $viewName = 'reports.pdfs.dashboard-summary-pdf'; // Nouvelle vue pour le PDF du tableau de bord
                break;

            default:
                abort(404, 'Type de rapport non trouvé.');
        }

        // Charger la vue Blade spécifique pour le PDF et générer le PDF
        $pdf = Pdf::loadView($viewName, $data);

        // Retourner le PDF en téléchargement
        return $pdf->download($filename . '.pdf');
    }
}
