<?php

namespace app\assets;

use app\assets\AbstractAsset;

/**
 * Задает пакет ресурсов для представления add-product
 */
class ShowProductsAdminAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, содержащиеся в данном комплекте
     */
    public $js = [
        'js/getSubcategory.js',
        'js/checkOneActive.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        'app\assets\MainAsset',
    ];
}
