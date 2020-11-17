<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Backend Progress',
    'description' => 'Show progress of long running tasks in backend.',
    'category' => 'be',
    'author' => 'Tizian Schmidlin',
    'author_email' => 'st@cabag.ch',
    'author_company' => 'cab services ag',
    'state' => 'stable',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '2.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.6-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => []
    ],
];
