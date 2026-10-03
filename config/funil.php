<?php

// Funil automático: anúncio → página → perguntas → oferta → teste → cobrança.
return [
    'trial_dias' => 14,

    // Plano oferecido no funil (o mesmo "Profissional" da página de planos)
    'plano' => [
        'nome'  => 'Profissional',
        'valor' => 97.00,
        'ciclo' => 'MONTHLY',
    ],

    // Quem recebe os avisos do funil (vazio = não envia e-mail)
    'notificar_email' => env('FUNIL_NOTIFY_EMAIL', 'rogernevesn@gmail.com'),

    // Conta Asaas do próprio SaaS (cobra as empresas que assinam). Sem chave, a compra fica desativada.
    'asaas' => [
        'url'           => env('ASAAS_SAAS_URL', 'https://api.asaas.com/v3'),
        'key'           => env('ASAAS_SAAS_KEY'),
        'webhook_token' => env('ASAAS_SAAS_WEBHOOK_TOKEN'),
    ],

    // Nicho escolhido no formulário → modalidade preferida (se existir no nicho do domínio)
    'modalidade_por_nicho' => [
        'turismo'   => 'Passeios',
        'surf'      => 'Surf',
        'bodyboard' => 'BodyBoard',
        'pilates'   => 'pilates',
    ],
];
