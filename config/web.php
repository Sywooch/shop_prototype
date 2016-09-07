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
                        'url'=>'yii\helpers\Url'
                    ],
                ]
            ],
        ],
        'request'=>[
            'cookieValidationKey'=>md5('Tyre7jh'),
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
            'enableStrictParsing' => true,
            'rules'=>require(__DIR__ . '/routes.php'),
        ],
        'session'=>[
            'class'=>'yii\web\DbSession',
            'timeout'=>60*60*24*7
        ],
        'filters'=>[
            'class'=>'app\models\FiltersModel',
        ],
    ],
    
    'params'=>require(__DIR__ . '/params.php'),
];

if (YII_DEBUG) {
    $config['as checkScriptInfoFilter'] = ['class'=>'app\filters\CheckScriptInfoFilter'];
}

return $config;
