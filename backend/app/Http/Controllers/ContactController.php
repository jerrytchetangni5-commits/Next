<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Il faut etre connecté pour envoyer un message'
            ], 401);
        }
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string'
        ]);

        $contact = Contact::create([
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $validated['phone_number'] ?? $user->phone_number ?? null,
            'message' => $validated['message']
        ]);

        try {
            Mail::send('emails.contact-admin', [
                'user' => $user,
                'message' => $validated['message'],
            ], function ($mail) use ($user) {
                $mail->to(env('ADMIN_EMAIL'),)
                    ->subject($user->first_name . ' vous a envoyé un message');
            });
        } catch (\Exception $e) {
            // On continue même si l'email échoue
        }
        return response()->json([
            'success' => true,
            'message' => 'Message envoyé',
            'data' => $contact
        ], 201);
    }
}
