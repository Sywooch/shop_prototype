<?php

namespace app\assets;

/**
 * Задает основной пакет ресурстов
 */
trait AssetTrait
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
