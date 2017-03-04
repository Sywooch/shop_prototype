<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает основной пакет ресурсов
 */
class AdminDeliveriesAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendAdminDeliveryForm.js',
        'js/sendAdminDeliveryCreate.js',
        'js/sendAdminDeliveryDelete.js',
        //'js/sendAdminCommentChange.js'
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
