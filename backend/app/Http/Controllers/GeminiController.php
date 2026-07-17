<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Services\AI\ChatService;

class GeminiController extends Controller
{
    public function chat(Request $request, ChatService $chatService)
    {
        $request->validate([
            'message' => 'required|string|max:' . config('gemini.max_message_length', 3000),
            'scholarship_id' => 'nullable|exists:scholarships,id',
            'filters' => 'nullable|array',
            'filters.country' => 'nullable|string',
            'filters.domain' => 'nullable|string',
            'filters.level' => 'nullable|string',
            'conversation_history' => 'nullable|array',
            'conversation_history.*.role' => 'required|string|in:user,assistant',
            'conversation_history.*.content' => 'required|string'
        ]);

        try{
            $response = $chatService->chat(
                $request->message,
                $request->only(['scholarship_id', 'filters', 'conversation_history'])
            );
            return response()->json([
                'success' => true,
                'data' => [
                    'answer' => $response
                ],
            ]);
        } catch (\Exception $e){
            \Log::error('GeminiController error: ' .$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Le service IA est indisponible pour le moment',
                'debug' => $e->getMessage()
            ], 503);
        }
    }
}
