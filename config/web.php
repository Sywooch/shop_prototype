<?php

$config = [
    'id'=>'shop',
    'basePath'=>dirname(__DIR__),
    'sourceLanguage'=>'en-US',
    'language'=>'ru-RU',
    'timeZone'=>'Europe/Kiev',
    'bootstrap'=>['log'],
    
    'aliases'=>[
        '@theme'=>'@app/themes/basic',
        '@imagesroot'=>'@app/web/sources/images/products',
        '@imagesweb'=>'/sources/images/products',
    ],
    
    'layout'=>'main.twig',
    'layoutPath'=>'@theme/layouts',
    
    'components'=>require(__DIR__ . '/components.php'),
    
    'modules'=>require(__DIR__ . '/modules.php'),
    
    'params'=>require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
}

return $config;
