<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'DashBash',
    'description' => 'Custom dashboard widgets for TYPO3',
    'category' => 'be',
    'author' => 'Marko Röper-Grewe',
    'author_email' => 'marko.roeper-grewe@udg.de',
    'author_company' => 'PIA / UDG',
    'state' => 'beta',
    'clearCacheOnLoad' => 1,
    'version' => '0.1.0',
    'iconIdentifier' => 'ext-dashbash-icon',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99',
            'dashboard' => '11.5.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'MRG\\Dashbash\\' => 'Classes/'
        ]
    ],
    'loadInBackend' => true,
];
