<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

unset($config['bootstrap'][1]);
unset($config['modules']);

$config['components']['db'] = $config['components']['dbTest'];
$config['components']['mailer']['useFileTransport'] = true;
$config['components']['mailer']['fileTransportPath'] = '@app/tests/sources/mail/letters';
$config['aliases']['@imagesroot'] = '@app/tests/sources/images/products';
$config['aliases']['@imagestemp'] = '@app/tests/sources/images/temp';
$config['aliases']['@csvroot'] = '@app/tests/sources/csv';
$config['params']['limit'] = 3;

Yii::$classMap['yii\web\UploadedFile'] = '@app/tests/MockUploadedFile.php';

(new yii\web\Application($config));
