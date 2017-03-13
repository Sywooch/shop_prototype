<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Общие настройки для классов Asset
 */
abstract class AbstractAsset extends AssetBundle
{
    /**
     * @var string корневая директория файлов ресурса
     */
    public $basePath = '@webroot/sources/themes/basic';
    /**
     * @var string web директория файлов ресурса
     */
    public $baseUrl = '@web/sources/themes/basic';
}
