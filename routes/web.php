<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;


// Route de la page d'accueil (accessible par tous)
Route::get('/', function () {
    // Si l'utilisateur est connecté, redirigez-le vers le tableau de bord
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Sinon, affichez la page d'accueil publique
    return view('layouts.home'); 
})->name('home');


Route::middleware('guest')->group(function () {
    // Routes d'inscription
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Routes de connexion
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post'); 

    
    // Routes de réinitialisation de mot de passe (Laravel par défaut)
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});


// Routes pour les utilisateurs authentifiés (Auth Middleware)
Route::middleware('auth')->group(function () {
    // Route du tableau de bord (après connexion)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route de déconnexion
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // route pour la page de profil/paramètres
     // Affichage du profil
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

     
     // Routes pour la gestion des produits
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Routes pour la gestion des ventes
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');   
    Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('/sales/cancel/{sale}', [SaleController::class, 'cancel'])->name('sales.cancel'); // Route pour annuler une vente

    // Routes pour la gestion des clients
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show'); 

    
    // Routes pour la gestion des crédits/dettes
    Route::resource('credits', CreditController::class);
    Route::get('/credits/{credit}/record-payment', [CreditController::class, 'showRecordPayment'])->name('credits.show_record_payment');
    Route::post('/credits/{credit}/payments', [CreditController::class, 'recordPayment'])->name('credits.record_payment');
    
        
     // Routes pour la gestion des rapports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daily-sales', [ReportController::class, 'dailySales'])->name('daily_sales');
        Route::get('/periodic-sales', [ReportController::class, 'periodicSales'])->name('periodic_sales');
        Route::get('/top-selling-products', [ReportController::class, 'topSellingProducts'])->name('top_selling_products');
        Route::get('/stock-report', [ReportController::class, 'stockReport'])->name('stock_report');
        Route::get('/credits-report', [ReportController::class, 'creditsReport'])->name('credits_report');
        // Route pour l'exportation en PDF (au lieu de CSV)
        Route::get('/export/{reportType}', [ReportController::class, 'exportPdf'])->name('export_pdf');
    });


    // Routes pour les notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark_as_read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark_all_as_read');
    });
    

});



