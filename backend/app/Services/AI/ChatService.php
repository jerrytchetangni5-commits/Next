<?php

namespace App\Services\AI;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class ChatService
{
    protected ContextBuilder $contextBuilder;
    protected PromptBuilder $promptBuilder;

    public function __construct(ContextBuilder $contextBuilder, PromptBuilder $promptBuilder)
    {
        $this->contextBuilder = $contextBuilder;
        $this->promptBuilder = $promptBuilder;
    }

    public function chat (string $message, array $payload = []): string
    {
        $contextResult = $this->contextBuilder->build($payload);
        $context = $contextResult['context'];

        $prompt = $this->promptBuilder->build($message, $context);

        try{
            $response = Gemini::generativeModel(config('gemini.model'))
                ->generateContent($prompt);
            return $response->text();
        } catch (\Exception $e){
            Log::error('Gemini error:' . $e->getMessage());
            throw $e;
        }
    }
}