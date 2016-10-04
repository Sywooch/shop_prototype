<?php

$config = [
    'id'=>'shop-console',
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'basePath'=>dirname(__DIR__),
    
    'components'=>[
        'db'=>require(__DIR__ . '/db.php'),
        'dbTest'=>require(__DIR__ . '/dbTest.php'),
        
        'i18n'=>[
            'translations'=>[
                'base*'=>[
                    'class'=>'yii\i18n\PhpMessageSource',
                    'fileMap'=>[
                        'base/console'=>'consoleTranslate.php'
                    ],
                ],
            ],
        ],
        
        'authManager'=>[
            'class'=>'yii\rbac\PhpManager',
        ],
    ],
    
    'controllerMap'=>[
        'tests'=>'app\console\TestsController',
        'seocode'=>'app\console\ProductsSeocodeController',
        'rbac'=>'app\console\RbacController',
    ],
];

return $config;
