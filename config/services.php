<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Alerte « enregistrements en échec » (app:attempts-health) : notification
    // HTTP (ex. https://ntfy.sh/<sujet>) et/ou e-mail. Vides = journaux seulement.
    'alerts' => [
        'webhook' => env('ALERT_WEBHOOK_URL', ''),
        'email' => env('ALERT_EMAIL', ''),
    ],

    // Voix de Mama Judi au prénom de l'enfant (accord du foyer requis).
    'elevenlabs' => [
        'key' => env('ELEVENLABS_API_KEY', ''),
        'voice_id' => env('ELEVENLABS_VOICE_ID', ''),
        'model' => env('ELEVENLABS_MODEL', 'eleven_multilingual_v2'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
