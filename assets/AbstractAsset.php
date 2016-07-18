<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Коллекция общих свойств для классов Asset
 */
abstract class AbstractAsset extends AssetBundle
{
    /**
     * @var string задаёт корневую директорию содержащую файлы ресурса
     */
    public $basePath = '@webroot/sources';
    /**
     * @var string задаёт Web доступную директорию, которая содержит файлы 
     */
    public $baseUrl = '@web/sources';
}
