<?php

$config = [
    'id'=>'shop-console',
    'basePath'=>dirname(__DIR__),
    'components'=>[
        //'db'=>YII_ENV_DEV ? require(__DIR__ . '/dbTest.php') : require(__DIR__ . '/db.php'),
        'db'=>require(__DIR__ . '/dbTest.php'),
    ],
];

return $config;
