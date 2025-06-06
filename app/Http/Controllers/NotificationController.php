<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\CustomNotification;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    /**
     * Affiche toutes les notifications d'un utilisateur.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications;

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Envoie une notification à un utilisateur spécifique.
     */
    public function send(User $user)
    {
        // Crée et envoie une notification à l'utilisateur
        $user->notify(new CustomNotification('Message personnalisé pour l\'utilisateur.'));

        return redirect()->route('notifications.index')->with('success', 'Notification envoyée avec succès.');
    }

    /**
     * Marque une notification comme lue.
     */
    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return redirect()->route('notifications.index')->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Supprime une notification.
     */
    public function destroy($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->delete();

        return redirect()->route('notifications.index')->with('success', 'Notification supprimée.');
    }

    /**
     * Envoie une notification à tous les utilisateurs.
     */
    public function sendToAll(Request $request)
    {
        // Validation du message
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Envoi de la notification uniquement aux utilisateurs avec le rôle "etudiant"
        $message = $request->input('message');
        
        //dd($message); 

        $etudiants = User::where('role', 'etudiant')->get();

        foreach ($etudiants as $etudiant) {
            $etudiant->notify(new CustomNotification($message));
        }

        return redirect()->route('notifications.index')->with('success', 'Notification envoyée à tous les étudiants.');
    }

    public function showSendToAllForm()
    {
        return view('notifications.send_to_all'); 
    }
}
