<?php

namespace app\assets;

use app\assets\AbstractAsset;

/**
 * Задает пакет ресурсов для представления add-product
 */
class GetSubcategoryWithDisableAsset extends AbstractAsset
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
        'js/getSubcategoryWithDisable.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        'app\assets\AbstractDropDownDisable',
        'app\assets\AbstractGetSubcategory',
    ];
}
