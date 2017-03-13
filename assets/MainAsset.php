<?php

namespace app\assets;

use yii\web\JqueryAsset;
use app\assets\AbstractAsset;

/**
 * Задает базовый пакет ресурсов
 */
class MainAsset extends AbstractAsset
{
    /**
     * @var array, CSS файлы комплекта
     */
    public $css = [
        'css/main.css',
    ];
    /**
     * @var array, JavaScript файлы комплекта
     */
    public $js = [
        'js/helpers.js',
        'js/abstractSendForm.js',
    ];
    /**
     * @var array, зависимости пакета
     */
    public $depends = [
        JqueryAsset::class,
    ];
}
