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
        'js/sendAdminMailingForm.js',
        'js/sendAdminMailingChange.js',
        'js/sendAdminMailingDelete.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
