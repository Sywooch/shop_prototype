<?php

$config = [
    'id'=>'shop',
    'basePath'=>__DIR__ . '/../',
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'timeZone'=>'Europe/Kiev',
    'layout'=>'main.twig',
    'params'=>[
        'filterKeys'=>['country'],
        'limit'=>20,
        'pagePointer'=>'page',
        'orderByRoute'=>'DESC',
    ],
    'components'=>[
        'db'=>require(__DIR__ . '/db.php'),
        'view'=>[
            'class'=>'yii\web\View',
            'renderers'=>[
                'twig'=>[
                    'class'=>'yii\twig\ViewRenderer',
                    'options'=>['auto_reload'=>true],
                    'globals'=>['html'=>'yii\helpers\Html'],
                ]
            ],
        ],
        'request'=>[
            'cookieValidationKey'=>md5('sLkuN'),
        ],
    ],
];

return $config;
