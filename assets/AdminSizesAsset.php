<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает пакет ресурсов для страницы администрирования цветов
 */
class AdminSizesAsset extends AbstractAsset
{
    /**
     * @var array, JavaScript файлы комплекта
     */
    public $js = [
        'js/adminSizes.js',
    ];
    /**
     * @var array, зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
