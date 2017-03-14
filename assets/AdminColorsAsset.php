<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает пакет ресурсов для страницы администрирования цветов
 */
class AdminColorsAsset extends AbstractAsset
{
    /**
     * @var array, JavaScript файлы комплекта
     */
    public $js = [
        'js/adminColors.js',
    ];
    /**
     * @var array, зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
