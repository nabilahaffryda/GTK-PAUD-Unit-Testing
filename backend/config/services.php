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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paspor' => [
        'url'        => env('PASPOR_URL', 'http://172.17.1.88:8080/siappassgpo/'),
        'layanan_id' => env('PASPOR_LAYANAN_ID', 35),
        'admin_id'   => env('PASPOR_ADMIN_ID', 1),
        'is_email'   => env('PASPOR_IS_EMAIL', '0'),
    ],

    'recaptcha' => [
        'url'        => env('RECAPTCHA_URL', 'https://www.google.com/recaptcha/api/'),
        'site_key'   => env('RECAPTCHA_SITE_KEY', '6LedDDAaAAAAAFZ9aI996UjWi48nTX80ZvD0M1St'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', '6LedDDAaAAAAAHrjOhJEihUhturglJKlFKg0MMrg'),
        'score'      => env('RECAPTCHA_SCORE', '0.3'),
    ],

    'simpkb-akun' => [
        'url'   => env('SIMPKB_AKUN_API_URL', 'https://akun-backend-dev.simpkb.id/graphql'),
        'token' => env('SIMPKB_AKUN_API_TOKEN', '17bd37723a9e24e9cbe4eed0e6b3287c'),
    ],

    'sertifikat-api' => [
        'url'   => env('SERTIFIKAT_API_URL', 'https://sertifikat-api-dev.simpkb.id/backend'),
        'token' => env('SERTIFIKAT_API_TOKEN', ''),
    ],

    'simpatika' => [
        'baseurl'  => env('SIMPATIKA_BASEURL', 'https://simpatika-api.local.siap.web.id'),
        'username' => env('SIMPATIKA_USERNAME', ''),
        'password' => env('SIMPATIKA_PASSWORD', ''),
    ],

    'elearning-api' => [
        'base-url'    => env('ELEARNING_API_BASEURL', 'https://api-elearning-ppgpaud.dev.simpkb.id/api/v1'),
        'token'       => env('ELEARNING_API_TOKEN', 'WaWb8g2N3pq0SyFYQ0lhKhMDX5RQK5wYz7Z6RwIp'),
        'log-request' => env('ELEARNING_API_LOG_REQUEST', true),
    ],
];
