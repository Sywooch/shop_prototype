<?php

$config = [
    'id'=>'shop',
    'basePath'=>__DIR__ . '/../',
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'timeZone'=>'Europe/Kiev',
    'layout'=>'main.twig',
    'params'=>[
        'filterKeys'=>['colors'], # Ключи, по которым в $_REQUEST доступны значения выбранных фильтров
        'limit'=>20, # Кол-во записей на страницу
        'pagePointer'=>'page', # Ключ, по которому в $_REQUEST доступен номер текущей страницы
        'orderByRoute'=>'DESC', # Порядок сортировки для БД
        'categoryKey'=>'category', # Ключ, по которому в $_REQUEST доступно название категории
        'subCategoryKey'=>'subcategory', # Ключ, по которому в $_REQUEST доступно название подкатегории
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
