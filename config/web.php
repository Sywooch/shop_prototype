<?php

$config = [
    'id'=>'shop',
    'basePath'=>__DIR__ . '/../',
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'timeZone'=>'Europe/Kiev',
    'params'=>[
        'filterKeys'=>['color', 'material', 'texture', 'currency'],
        'limit'=>20,
        'pagePointer'=>'page',
        'orderByRoute'=>'DESC',
    ],
    'components'=>[
        'db'=>require(__DIR__ . '/db.php'),
    ],
];

return $config;
