<?php
return [
    'userDataEditing' => [
        'type' => 2,
        'description' => 'User data editing',
        'ruleName' => 'isOwnUserData',
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'userDataEditing',
        ],
    ],
];
