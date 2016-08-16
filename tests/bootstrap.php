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

$config['aliases']['@pic'] = '/var/www/html/shop/tests/source/images';
$config['components']['mailer']['useFileTransport'] = true;
$config['components']['mailer']['fileTransportPath'] = '@app/tests/source/mail/letters';

(new yii\web\Application($config));

