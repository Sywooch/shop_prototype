<?php

$config = [
    'id'=>'shop',
    'basePath'=>dirname(__DIR__),
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'timeZone'=>'Europe/Kiev',
    'bootstrap'=>['log'],
    
    'aliases'=>[
        '@theme'=>'@app/themes/basic'
    ],
    
    'layout'=>'main.twig',
    'layoutPath'=>'@theme/layouts',
    
    'components'=>require(__DIR__ . '/components.php'),
    
    'params'=>require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    $config['as checkScriptInfoFilter'] = ['class'=>'app\filters\CheckScriptInfoFilter'];
}

return $config;
