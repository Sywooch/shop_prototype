<?php

$config = [
    'id'=>'shop',
    'basePath'=>__DIR__ . '/../',
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'timeZone'=>'Europe/Kiev',
    'layout'=>'main.twig',
    'bootstrap'=>['log'],
    'params'=>[
        'filterKeys'=>['colors', 'sizes', 'brands'], # Ключи, по которым в $_REQUEST доступны значения выбранных фильтров
        'limit'=>20, # Кол-во записей на страницу
        'pagePointer'=>'page', # Ключ, по которому в $_REQUEST доступен номер текущей страницы
        'orderFieldPointer'=>'orderby',# Ключ, по которому в $_REQUEST доступно поле сортировки
        'orderTypePointer'=>'ordertype',# Ключ, по которому в $_REQUEST доступен порядок сортировки
        'orderByType'=>'DESC', # Порядок сортировки для БД
        'categoryKey'=>'categories', # Ключ, по которому в $_REQUEST доступно название категории
        'subCategoryKey'=>'subcategory', # Ключ, по которому в $_REQUEST доступно название подкатегории
        'searchKey'=>'search', # Ключ, по которому в $_REQUEST доступно значение для поиска
        'idKey'=>'id', # Ключ, по которому в $_REQUEST доступно значение id продукта
        'fixSentRequests'=>0 #Количество запросов к БД при выполнении скрипта
    ],
    'components'=>[
        'db'=>require(__DIR__ . '/db.php'),
        'view'=>[
            'class'=>'yii\web\View',
            'renderers'=>[
                'twig'=>[
                    'class'=>'yii\twig\ViewRenderer',
                    'options'=>['auto_reload'=>true],
                    'globals'=>[
                        'html'=>'yii\helpers\Html', 
                        'url'=>'yii\helpers\Url',
                        'getParams'=>'app\helpers\AddGetParamsHelper',
                    ],
                ]
            ],
        ],
        'request'=>[
            'cookieValidationKey'=>md5('sLkuN'),
        ],
        'log'=>[
            'traceLevel'=>YII_DEBUG ? 3 : 0,
            'flushInterval'=>100,
            'targets'=>[
                'file'=>[
                    'class'=>'yii\log\FileTarget',
                    'logFile'=>__DIR__ . '/../logs/error.log',
                    'levels'=>['error', 'warning'],
                    'exportInterval'=>100,
                ],
            ],
        ],
        'urlManager'=>[
            'enablePrettyUrl'=>true,
            'showScriptName'=>false,
            'enableStrictParsing' => false,
            'rules'=>[
                'products/<categories>/<subcategory>/<id:\d+>'=>'product-detail/index',
                'products/<categories>/<subcategory>'=>'products-list/index',
                'products/<categories>'=>'products-list/index',
                'products'=>'products-list/index',
                'search'=>'products-list/search',
            ],
        ],
    ],
];

if (YII_DEBUG) {
    $config['as CheckScriptInfoFilter'] = ['class'=>'app\filters\CheckScriptInfoFilter'];
}

return $config;
