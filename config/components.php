<?php

$components = [
    'db'=>require(__DIR__ . '/db.php'),
    'dbTest'=>require(__DIR__ . '/dbTest.php'),
    
    'view'=>[
        'class'=>'yii\web\View',
        'renderers'=>[
            'twig'=>[
                'class'=>'yii\twig\ViewRenderer',
                'options'=>['auto_reload'=>true],
                'globals'=>[
                    'url'=>'yii\helpers\Url',
                    'yii'=>'\Yii',
                    'array'=>'yii\helpers\ArrayHelper'
                ],
            ]
        ],
        'theme'=>[
            'basePath'=>'@webroot/sources/themes/basic',
            'baseUrl'=>'@web/sources/themes/basic',
            'pathMap'=>[
                '@app/views'=>'@theme'
            ],
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
    
    'i18n'=>[
        'translations'=>[
            'base*'=>[
                'class'=>'yii\i18n\PhpMessageSource',
                'fileMap'=>[
                    'base'=>'baseTranslate.php',
                    'base/errors'=>'errorsTranslate.php'
                ],
            ],
        ],
    ],
    
    'session'=>[
        'class'=>'yii\redis\Session',
        'timeout'=>60*60*24*7
    ],
    
    'sphinx'=>[
        'class'=>'yii\sphinx\Connection',
        'dsn'=>'mysql:host=127.0.0.1;port=9306;dbname=shop',
        'username'=>'shopadmin',
        'password'=>'shopadmin',
        'charset'=>'utf8',
    ],
    
    'redis'=>[
        'class'=>'yii\redis\Connection',
        'hostname'=>'localhost',
        'port'=>6379,
        'database'=>0,
        'password'=>'01321d2b9eecce37ab71db2e20ad67f9'
    ],
    
    'user'=>[
        'class'=>'yii\web\User',
        'identityClass'=>'app\models\UsersModel',
    ],
    
    'authManager'=>[
        'class'=>'yii\rbac\PhpManager',
    ],
    
    'mailer'=>[
        'class'=>'yii\swiftmailer\Mailer',
        'viewPath'=>'@theme/mail',
    ],
    
    'formatter'=>[
        'thousandSeparator'=>'',
        'decimalSeparator'=>',',
    ],
    
    'filters'=>[
        'class'=>'app\models\FiltersModel',
    ],
    
    'currency'=>[
        'class'=>'app\models\CurrencyModel',
    ],
];

return $components;
