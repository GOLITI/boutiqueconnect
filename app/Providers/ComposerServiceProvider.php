<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Importez la façade View
use Illuminate\Support\Facades\Auth; // Importez la façade Auth
use App\Models\Notification; // Importez le modèle Notification

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partagez le nombre de notifications non lues avec la vue 'layouts.app'
        View::composer('layouts.app', function ($view) {
            $unreadNotificationsCount = 0;
            if (Auth::check()) {
                 /** @var \App\Models\User $user */
                 $user = Auth::user();
                $unreadNotificationsCount = $user->notifications()->where('is_read', false)->count();
            }
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
        });
    }
}
