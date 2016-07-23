<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Коллекция методов, обеспечивающих загрузку файлов
 */
class UploadHelper
{
    use ExceptionsTrait;
    
    /**
     * @var string имя категории для текущей группы файлов
     */
    private static $_catalogName = '';
    /**
     * @var string полный финальный путь к папке с загруженными файлами
     */
    private static $_fullPath = '';
    
    /**
     * Сохраняет файлы изображений
     * @param array of objects
     * @return boolean
     */
    public static function saveImages(Array $imagesArray)
    {
        try {
            if (!self::setCatalogNameAndFullPath()) {
                throw new ErrorException('Ошибка при вызове setCatalogNameAndFullPath!');
            }
            foreach ($imagesArray as $image) {
                $image->saveAs(self::$_fullPath . '/' . $image->baseName . '.' . $image->extension);
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает имя категории для текущей группы файлов, 
     * полный финальный путь к папке с загруженными файлами,
     * создает каталог
     * @return string 
     */
    private static function setCatalogNameAndFullPath()
    {
        try {
            self::$_catalogName = time();
            self::$_fullPath = \Yii::getAlias('@pic/' . self::$_catalogName);
            if (!file_exists(self::$_fullPath)) {
                mkdir(self::$_fullPath, 0775);
            }
            if (file_exists(self::$_fullPath) && is_dir(self::$_fullPath)) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает имя каталога, по которому в БД доступны изображения
     * @return string
     */
    public static function getСatalogName()
    {
        try {
            return self::$_catalogName;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
