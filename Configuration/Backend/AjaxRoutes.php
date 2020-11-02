<?php

return [
    'backend_progress' => [
        'path' => '/backend_progress',
        'target' => \Cabag\BackendProgress\Controller\BackendProgressController::class . '::getProgress'
    ]
];
