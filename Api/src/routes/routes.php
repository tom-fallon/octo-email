<?php
use Api\src\Controllers\ContactController;

return [
    '/email\/list/m' => [
        'controller' => ContactController::class,
        'method' => 'list'
    ],
    '/email\/create/m' => [
        'controller' => ContactController::class,
        'method' => 'create'
    ],
    '/email\/delete\/(\d*)/m' => [
        'controller' => ContactController::class,
        'method' => 'delete'
    ],
];
