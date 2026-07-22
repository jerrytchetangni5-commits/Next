<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LibreTranslateService
{
    protected string $url;

    public function __construct()
    {
        $this->url = config('services.libretranslate.url', 'https://libretranslate.com/translate');
    }

    //Traduit plusieurs textes en une seule requête HTTP avec cache.
    public function translateFields(array $fields): array
    {
        $result = [];
        $toTranslate = [];

        // Vérifier le cache
        foreach ($fields as $key => $value) {
            if (empty($value)) {
                $result[$key] = null;
                continue;
            }

            $cacheKey = 'translation_en_fr_' . md5($value);
            if (Cache::has($cacheKey)) {
                $result[$key] = Cache::get($cacheKey);
            } else {
                $toTranslate[$key] = $value;
            }
        }

        if (empty($toTranslate)) {
            return $result;
        }

        // Batch avec séparateur
        $separator = '|||';
        $combined = implode($separator, array_values($toTranslate));

        try {
            $response = Http::timeout(60)->post($this->url, [
                'q' => $combined,
                'source' => 'en',
                'target' => 'fr',
                'format' => 'text'
            ]);

            $apiKey = config('services.libretranslate.api_key');
            if ($apiKey) {
                $payload['api_key'] = $apiKey;
            }

            $response = Http::timeout(60)->post($this->url, $payload);

            if ($response->failed()) {
                Log::error('LibreTranslate batch error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return array_merge($result, $toTranslate);
            }

            $translatedCombined = $response->json()['translatedText'] ?? null;
            if (!$translatedCombined) {
                return array_merge($result, $toTranslate);
            }

            $translatedParts = explode($separator, $translatedCombined);
            $keys = array_keys($toTranslate);

            foreach ($keys as $index => $key) {
                $translatedText = $translatedParts[$index] ?? $toTranslate[$key];
                $result[$key] = $translatedText;
                Cache::put('translation_en_fr_' . md5($toTranslate[$key]), $translatedText, now()->addDays(30));
            }

            return $result;

        } catch (\Throwable $e) {
            Log::error('LibreTranslate exception', ['message' => $e->getMessage()]);
            return array_merge($result, $toTranslate);
        }
    }

    //Traduit un seul texte.
    public function translate(?string $text): ?string
    {
        if (empty($text)) {
            return null;
        }
        $result = $this->translateFields(['text' => $text]);
        return $result['text'] ?? $text;
    }
}