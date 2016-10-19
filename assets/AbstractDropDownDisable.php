<?php

namespace app\assets;

use app\assets\AbstractAsset;

/**
 * Задает пакет ресурсов для представления add-product
 */
class AbstractDropDownDisable extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, содержащиеся в данном комплекте
     */
    public $js = [
        'js/dropDownDisable.js',
    ];
}
