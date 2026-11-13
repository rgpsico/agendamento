<?php

/* Chaves VAPID do Web Push (aviso no celular do barbeiro). Gere o par com Minishlink\WebPush\VAPID::createVapidKeys() */
return [
    'public_key' => env('VAPID_PUBLIC_KEY'),
    'private_key' => env('VAPID_PRIVATE_KEY'),
    'subject' => env('VAPID_SUBJECT', env('APP_URL', 'https://localhost')),
];
