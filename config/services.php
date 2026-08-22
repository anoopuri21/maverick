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

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'upload_folder' => env('CLOUDINARY_UPLOAD_FOLDER', 'maverick-academy'),
        // false (default) = one shared folder for all environments.
        // true = append APP_ENV (or env_prefix) so local/prod use different folders.
        'env_folder' => filter_var(env('CLOUDINARY_ENV_FOLDER', false), FILTER_VALIDATE_BOOLEAN),
        'env_prefix' => env('CLOUDINARY_ENV_PREFIX'),
        'disk_env' => env('CLOUDINARY_DISK_ENV', 'shared'),
        'legacy_env_suffixes' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('CLOUDINARY_LEGACY_ENV_SUFFIXES', 'local,testing,staging,development,dev'))
        ))),
        'secure' => true,
    ],

    'zapier' => [
        'contact_webhook_url' => env('ZAPIER_CONTACT_WEBHOOK_URL'),
    ],
];
