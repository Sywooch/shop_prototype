<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;
use yii\base\ErrorException;

/**
 * Коллекция методов для обработки изображений
 */
class PicturesHelper
{
    use ExceptionsTrait;
    
    /**
     * @var object экземпляр Imagick в текущей итерации
     */
    private static $_objectImagick;
    
    /**
     * @var array массив объектов yii\web\UploadedFile, содержит данные:
     * 
     */
    private static $_picturesArray;
    
    /**
     * Обрезает изображение до указанных как максимальные размеров
     * @param array of objects массив объектов yii\web\UploadedFile
     * @return boolean
     */
    public static function thumbnail(Array $objectsArray)
    {
        try {
            foreach ($objectsArray as $objImg) {
                self::$_objectImagick = new \Imagick($objImg->tempName);
                $width = self::getWidth();
                $height = self::getHeight();
                
                if ($width > \Yii::$app->params['maxWidth'] || $height > \Yii::$app->params['maxHeight']) {
                    if (!self::crop()) {
                        throw new ErrorException('Ошибка при масштабировании изображения!');
                    }
                    if (!self::$_objectImagick->writeImage($objImg->tempName)) {
                        throw new ErrorException('Ошибка при сохранении изображения!');
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает ширину изображения
     * @return int
     */
    private static function getWidth()
    {
        try {
            return self::$_objectImagick->getImageWidth();
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает высоту изображения
     * @return int
     */
    private static function getHeight()
    {
        try {
            return self::$_objectImagick->getImageHeight();
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Уменьшает изображение до максимально допустимого размера
     * @param object \Imagick
     */
    private static function crop()
    {
        try {
            return self::$_objectImagick->thumbnailImage(\Yii::$app->params['maxWidth'], \Yii::$app->params['maxHeight'], TRUE);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
