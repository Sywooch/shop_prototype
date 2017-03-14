<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает пакет ресурсов для страницы администрирования заказов
 */
class AdminCategoriesAsset extends AbstractAsset
{
    /**
     * @var array, JavaScript файлы комплекта
     */
    public $js = [
        'js/adminCategories.js',
    ];
    /**
     * @var array, зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
