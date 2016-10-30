<?php

namespace app\assets;

use app\assets\AbstractAsset;

/**
 * Задает основной пакет ресурсов
 */
class AddCustomerAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/customerAdd.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        'app\assets\MainAsset',
    ];
}
