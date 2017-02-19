<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает основной пакет ресурсов
 */
class AdminAddProductAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendGetSubcategory.js',
        'js/sendAddProductForm.js',
        'js/disableFirstSelect.js'
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
