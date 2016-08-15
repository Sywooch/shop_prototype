<?php

namespace app\assets;

use app\assets\AbstractAsset;

/**
 * Задает пакет ресурсов для представления add-product
 */
class ConvertAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, содержащиеся в данном комплекте
     */
    public $js = [
        'js/getCSV.js',
        'js/getSubcategory.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        'app\assets\MainAsset',
    ];
}
