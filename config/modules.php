<?php

$modules = [];

if (YII_ENV_DEV) {
    $modules['debug'] = ['class'=>'yii\debug\Module'];
}

return $modules;
