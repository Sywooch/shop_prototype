<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    AbstractSendFormAsset,
    MainAsset};

/**
 * Задает основной пакет ресурсов
 */
class AdminOrdersAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendAdminOrderDetailForm.js',
        'js/sendAdminOrderDetailChange.js',
        'js/sendGetCalendar.js'
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
        AbstractSendFormAsset::class,
    ];
}
