<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'SCSS Compiler',
    'description' => 'Compile scss files with scssphp/scssphp across different extensions',
    'category' => 'plugin',
    'author' => 'Manuel Schnabel',
    'author_email' => 'service@passionweb.de',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
