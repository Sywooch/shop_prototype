<?php

namespace app\assets;

use yii\web\JqueryAsset;
use app\assets\AbstractAsset;

/**
 * Задает основной пакет ресурсов
 */
class MainAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий CSS файлы, содержащиеся в данном комплекте
     */
    public $css = [
        'css/main.css',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        JqueryAsset::class,
    ];
}
