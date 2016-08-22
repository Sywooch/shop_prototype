<?php

namespace app\assets;

use app\assets\AbstractAsset;

/**
 * Задает основной пакет ресурсов
 */
class AbstractGetSubcategory extends AbstractAsset
{
    /**
     * @var array массив js параметров
     */
    public $jsOptions = [
        'defer'=>true,
    ];
    /**
     * @var array массив, перечисляющий JavaScript файлы, содержащиеся в данном комплекте
     */
    public $js = [
        'js/abstractGetSubcategory.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        'app\assets\MainAsset',
    ];
}
