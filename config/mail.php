<?php

$mailPort = (int) env('MAIL_PORT', 2525);
$defaultSmtpScheme = env('MAIL_SCHEME');
if ($defaultSmtpScheme === null || $defaultSmtpScheme === '') {
    $defaultSmtpScheme = $mailPort === 465 ? 'smtps' : null;
}

$verifySsl = filter_var(env('MAIL_VERIFY_SSL', true), FILTER_VALIDATE_BOOL);

return [

    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => $defaultSmtpScheme,
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => $mailPort ?: 2525,
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => env('MAIL_TIMEOUT') !== null && env('MAIL_TIMEOUT') !== '' ? (int) env('MAIL_TIMEOUT') : 30,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            // Symfony Mailer: false ajuda em hospedagem com certificados problemáticos (use só se necessário)
            'verify_peer' => $verifySsl,
        ],

        /*
        | Hostinger: se a porta 465 falhar (firewall), use no .env:
        | MAIL_MAILER=hostinger
        | (mantém MAIL_USERNAME / MAIL_PASSWORD iguais)
        */
        'hostinger' => [
            'transport' => 'smtp',
            'scheme' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.hostinger.com'),
            'port' => (int) env('MAIL_ALT_PORT', 587),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => env('MAIL_TIMEOUT') !== null && env('MAIL_TIMEOUT') !== '' ? (int) env('MAIL_TIMEOUT') : 30,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            'verify_peer' => $verifySsl,
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    'markdown' => [
        'theme' => env('MAIL_MARKDOWN_THEME', 'default'),

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
