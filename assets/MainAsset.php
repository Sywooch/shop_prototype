<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Задает основной пакет ресурстов
 */
class MainAsset extends AssetBundle
{
    /**
     * @var string задаёт корневую директорию содержащую файлы ресурса
     */
    public $basePath = '@webroot/sources';
    /**
     * @var string задаёт Web доступную директорию, которая содержит файлы 
     */
    public $baseUrl = '@web/sources';
    /**
     * @var array массив, перечисляющий CSS файлы, содержащиеся в данном комплекте
     */
    public $css = [
        'css/main.css',
    ];
    /**
     * @var array массив, перечисляющий JavaScript файлы, содержащиеся в данном комплекте
     */
    public $js = [
        'js/getSubcategory.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
