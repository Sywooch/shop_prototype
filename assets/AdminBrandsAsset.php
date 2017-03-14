<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает пакет ресурсов для страницы администрирования брендов
 */
class AdminBrandsAsset extends AbstractAsset
{
    /**
     * @var array, JavaScript файлы комплекта
     */
    public $js = [
        'js/adminBrands.js',
    ];
    /**
     * @var array, зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
