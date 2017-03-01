<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    MainAsset};

/**
 * Задает основной пакет ресурсов
 */
class AdminCommentsAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendAdminCommentsGetCsv.js',
        'js/sendAdminCommentForm.js',
        'js/sendAdminCommentChange.js'
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
    ];
}
