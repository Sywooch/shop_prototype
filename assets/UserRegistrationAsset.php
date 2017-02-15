<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает основной пакет ресурсов
 */
class UserRegistrationAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendUserRegistration.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
