<?php

$config = [
    'id'=>'shop',
    'basePath'=>dirname(__DIR__),
    'sourceLanguage'=>'en-US',
    'language'=>'ru-RU',
    'timeZone'=>'Europe/Kiev',
    'bootstrap'=>['log', 'debug'],
    
    'aliases'=>[
        '@theme'=>'@app/themes/basic'
    ],
    
    'layout'=>'main.twig',
    'layoutPath'=>'@theme/layouts',
    
    'components'=>require(__DIR__ . '/components.php'),
    
    'modules'=>require(__DIR__ . '/modules.php'),
    
    'params'=>require(__DIR__ . '/params.php'),
];

return $config;
