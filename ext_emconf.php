<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'DashBash',
    'description' => 'Custom dashboard widgets for TYPO3',
    'category' => 'be',
    'author' => 'Marko Röper-Grewe',
    'author_email' => 'marko.roeper-grewe@udg.de',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99',
            'dashboard' => '11.5.0-12.4.99',
        ],
    ],
];
