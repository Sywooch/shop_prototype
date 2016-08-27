<?php

$config = [
    'id'=>'shop',
    'basePath'=>__DIR__ . '/../',
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'timeZone'=>'Europe/Kiev',
    'layout'=>'main.twig',
    'bootstrap'=>['log'],
    
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
                        'array'=>'yii\helpers\ArrayHelper',
                        'url'=>'yii\helpers\Url',
                        'objectsToArrayHelper'=>'app\helpers\ObjectsToArrayHelper',
                        'picturesHelper'=>'app\helpers\PicturesHelper',
                        'subcategoryHelper'=>'app\helpers\SubcategoryHelper',
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
        'assetManager'=>[
            'bundles'=>[
                'yii\web\JqueryAsset'=>[
                    'sourcePath'=>null,
                    'js'=>['https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js']
                ],
            ],
            'basePath'=>'@webroot/sources/temp',
            'baseUrl'=>'@web/sources/temp',
            'appendTimestamp'=>true
        ],
        'urlManager'=>[
            'enablePrettyUrl'=>true,
            'showScriptName'=>false,
            'enableStrictParsing' => false,
            'rules'=>require(__DIR__ . '/routes.php'),
        ],
        'cart'=>[
            'class'=>'app\cart\ShoppingCart',
        ],
        'filters'=>[
            'class'=>'app\models\FiltersModel',
        ],
        'shopUser'=>[
            'class'=>'app\models\UsersModel',
        ],
        'session'=>[
            'class'=>'yii\web\DbSession',
            'timeout'=>60*60*24*7
        ],
        'mailer'=>[
            'class'=>'yii\swiftmailer\Mailer',
            'viewPath'=>'@app/views/mail',
        ],
        'formatter'=>[
            'dateFormat'=>'dd.MM.yyyy',
        ],
    ],
    
    'aliases'=>[
        '@pic'=>'/var/www/html/shop/web/sources/images/products',
        '@wpic'=>'/sources/images/products',
    ],
    
    'as shoppingCartFilter'=>['class'=>'app\filters\ShoppingCartFilter'],
    'as usersFilter'=>['class'=>'app\filters\UsersFilter'],
    
    'params'=>require(__DIR__ . '/params.php'),
];

if (YII_DEBUG) {
    $config['as checkScriptInfoFilter'] = ['class'=>'app\filters\CheckScriptInfoFilter'];
    $config['as csrfSwitch'] = ['class'=>'app\filters\CsrfSwitch'];
}

return $config;
