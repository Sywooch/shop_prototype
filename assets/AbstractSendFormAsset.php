<?php

namespace app\assets;

use app\assets\AbstractAsset;

/**
 * Задает основной пакет ресурсов
 */
class AbstractSendFormAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/abstractSendForm.js',
    ];
}
