<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Envoie un message de contact.
     * Seul un utilisateur connecté peut envoyer un message.
     */
    public function store(Request $request)
    {
        // 1. Vérifier que l'utilisateur est connecté
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour envoyer un message.'
            ], 401);
        }

        // 2. Valider les données
        $validated = $request->validate([
            'message' => 'required|string',
            'phone_number' => 'nullable|integer',
        ]);

        // 3. Sauvegarder le message en base
        $contact = Contact::create([
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $validated['phone_number'] ?? null,
            'message' => $validated['message'],
        ]);

        // 4. Envoyer un email à l'admin (optionnel)
        try {
            Mail::send('emails.contact-admin', [
                'user' => $user,
                'message' => $validated['message'],
            ], function ($mail) use ($user) {
                $mail->to(env('ADMIN_EMAIL'))
                    ->subject('Nouveau message de contact - Next');
            });
        } catch (\Exception $e) {
            // On continue même si l'email échoue (le message est déjà sauvegardé)
            \Log::error('Erreur envoi email contact : ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès',
            'data' => $contact
        ], 201);
    }
}