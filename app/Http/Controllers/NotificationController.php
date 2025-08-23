<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification; // N'oubliez pas d'importer le modèle Notification

class NotificationController extends Controller
{
    /**
     * Affiche la liste des notifications pour l'utilisateur connecté.
     */
    public function index()
    {
         /** @var \App\Models\User $user */
        $user = Auth::user();
        // Récupère les notifications, les trie par les plus récentes
        // et les pagine si nécessaire.
        $notifications = $user->notifications()->latest()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marque une notification spécifique comme lue.
     */
    public function markAsRead(Notification $notification)
    {
        // Assurez-vous que l'utilisateur connecté est bien le propriétaire de la notification
        if (Auth::id() !== $notification->user_id) {
            abort(403, 'Accès non autorisé.');
        }

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marque toutes les notifications non lues comme lues pour l'utilisateur connecté.
     */
    public function markAllAsRead()
    {
         /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
