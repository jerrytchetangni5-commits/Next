<?php

namespace App\Services\AI;

use App\AI\Prompts\SystemPrompt;

class PromptBuilder
{
    public function build(string $userMessage, string $context): string
    {
        $systemPrompt = SystemPrompt::get();
        $fullPrompt = $systemPrompt . "\n\n";

        if ($context){
            //l'opérateur ".=" signifie ajouté à la suite de la variable existante (concaténation)
            $fullPrompt .= "**Contexte (données de la plateforme Next) :**\n";
            $fullPrompt .= $context . "\n";
        }

        //On ajoute la question de l'utilisateur toujours à la fin (Recency Bias)
        $fullPrompt .= "**Question de l'utilisateur :**\n" . $userMessage;
        return $fullPrompt;
    }
}