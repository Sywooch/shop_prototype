<?php
return [
    'adminIndexPermission' => [
        'type' => 2,
    ],
    'adminOrdersPermission' => [
        'type' => 2,
    ],
    'superUser' => [
        'type' => 1,
        'children' => [
            'adminIndexPermission',
            'adminOrdersPermission',
        ],
    ],
];
