<?php

return [
    'translate_enabled' => env('APP_TRANSLATE', false),
    'default_locale' => env('APP_LOCALE', 'fr'),
    'supported_locales' => ['fr', 'en'],
];