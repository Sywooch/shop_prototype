<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    AbstractSendFormAsset,
    MainAsset};

/**
 * Задает пакет ресурсов
 */
class AccountChangeSubscriptionsAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendAccountChangeSubscriptions.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
        AbstractSendFormAsset::class,
    ];
}
