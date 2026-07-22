<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Connectez-vous'], 401);
    }

    $validated = $request->validate([
        'message' => 'required|string',
        'phone_number' => 'nullable|string',
    ]);

    $contact = Contact::create([
        'user_id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'phone_number' => $validated['phone_number'] ?? null,
        'message' => $validated['message'],
    ]);

    // Envoi email à l'admin
    Mail::to('setonjerry23@gmail.com')->send(...);

    return response()->json(['success' => true, 'data' => $contact]);
}
}
