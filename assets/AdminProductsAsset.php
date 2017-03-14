<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает пакет ресурсов для страницы редактирования товаров
 */
class AdminProductsAsset extends AbstractAsset
{
    /**
     * @var array, JavaScript файлы комплекта
     */
    public $js = [
        'js/getSubcategory.js',
        'js/sendFile.js',
        'js/adminProducts.js',
    ];
    /**
     * @var array, зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
