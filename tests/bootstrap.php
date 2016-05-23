<?php

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

$config['components']['db'] = new \yii\db\Connection([
    'dsn'=>'mysql:host=localhost;dbname=shop_test',
    'username'=>'root',
    'password'=>'estet234',
    'charset'=>'utf8'
]);

(new yii\web\Application($config));

