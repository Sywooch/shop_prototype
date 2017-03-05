<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает основной пакет ресурсов
 */
class AdminMailingsAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendAdminMailingCreate.js',
        /*
        'js/sendAdminPaymentForm.js',
        'js/sendAdminPaymentChange.js',
        'js/sendAdminPaymentDelete.js',*/
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
