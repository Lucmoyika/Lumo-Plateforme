<?php

return [
    'trial_days' => 30,

    // Sous-modules adaptes au contexte RDC
    'submodules' => [
        'mp' => [
            'key' => 'mp',
            'label' => 'Maternelle & Primaire',
            'level_types' => ['maternelle', 'primaire'],
            'mobile_enabled' => true,
        ],
        'ps' => [
            'key' => 'ps',
            'label' => 'Primaire & Secondaire',
            'level_types' => ['primaire', 'secondaire'],
            'mobile_enabled' => true,
        ],
        'sh' => [
            'key' => 'sh',
            'label' => 'Secondaire & Humanites',
            'level_types' => ['secondaire', 'humanites'],
            'mobile_enabled' => true,
        ],
        'full' => [
            'key' => 'full',
            'label' => 'Maternelle, Primaire, Secondaire & Humanites',
            'level_types' => ['maternelle', 'primaire', 'secondaire', 'humanites'],
            'mobile_enabled' => true,
        ],
    ],

    // Les prix sont en CDF
    'plans' => [
        'mp_monthly' => [
            'code' => 'mp_monthly',
            'submodule_key' => 'mp',
            'label' => 'MP Mensuel',
            'duration_months' => 1,
            'price_cdf' => 65000,
            'trial_days' => 30,
            'mobile_enabled' => true,
        ],
        'mp_yearly' => [
            'code' => 'mp_yearly',
            'submodule_key' => 'mp',
            'label' => 'MP Annuel',
            'duration_months' => 12,
            'price_cdf' => 680000,
            'trial_days' => 30,
            'mobile_enabled' => true,
        ],
        'sh_monthly' => [
            'code' => 'sh_monthly',
            'submodule_key' => 'sh',
            'label' => 'SH Mensuel',
            'duration_months' => 1,
            'price_cdf' => 85000,
            'trial_days' => 30,
            'mobile_enabled' => true,
        ],
        'sh_yearly' => [
            'code' => 'sh_yearly',
            'submodule_key' => 'sh',
            'label' => 'SH Annuel',
            'duration_months' => 12,
            'price_cdf' => 920000,
            'trial_days' => 30,
            'mobile_enabled' => true,
        ],
        'full_monthly' => [
            'code' => 'full_monthly',
            'submodule_key' => 'full',
            'label' => 'FULL Mensuel',
            'duration_months' => 1,
            'price_cdf' => 120000,
            'trial_days' => 30,
            'mobile_enabled' => true,
        ],
        'full_yearly' => [
            'code' => 'full_yearly',
            'submodule_key' => 'full',
            'label' => 'FULL Annuel',
            'duration_months' => 12,
            'price_cdf' => 1290000,
            'trial_days' => 30,
            'mobile_enabled' => true,
        ],
    ],
];
