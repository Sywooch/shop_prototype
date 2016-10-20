<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\web\UploadedFile;
use app\exceptions\ExceptionsTrait;

/**
 * Коллекция методов для обработки изображений
 */
class PicturesHelper
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает переданный объект yii\web\UploadedFile
     * @param array $image объект yii\web\UploadedFile
     * @return boolean
     */
    public static function resize(UploadedFile $image)
    {
        try {
           $imageImagick = new \Imagick($image->tempName);
            if (!self::process($imageImagick, \Yii::$app->params['maxWidth'], \Yii::$app->params['maxHeight'], $image->tempName)) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PicturesHelper::process']));
            }
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Создает эскизы изображений
     * @param string $path путь к папке, в которой необходимо создать эскизы
     * @return boolean
     */
    public static function createThumbnails($path)
    {
        try {
            if (!file_exists($path) && !is_dir($path)) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PicturesHelper::createThumbnails']));
            }
            $imagesArray = glob($path . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            if (empty($imagesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'$imagesArray']));
            }
            foreach ($imagesArray as $image) {
                $imageImagick = new \Imagick($image);
                $dirname = dirname($image);
                $filename = basename($image);
                $thumbnailPath = $dirname . '/' . \Yii::$app->params['thumbnailPrefix'] . $filename;
                if (!self::process($imageImagick, \Yii::$app->params['maxThumbnailWidth'], \Yii::$app->params['maxThumbnailHeight'], $thumbnailPath)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PicturesHelper::process']));
                }
            }
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Анализирует и обрабатывает изображение
     * @param object $imageImagick объект Imagick, который будет обработан
     * @param string $maxWidth максимально допустимая ширина
     * @param string $maxHeight максимально допустимая высота
     * @param string $path путь сохранения обработанного изображения
     * @return boolean
     */
    private static function process($imageImagick, $maxWidth, $maxHeight, $path)
    {
        try {
            $currentWidth = $imageImagick->getImageWidth();
            $currentHeight = $imageImagick->getImageHeight();
            if ($currentWidth > $maxWidth || $currentHeight > $maxHeight) {
                if (!$imageImagick->thumbnailImage($maxWidth, $maxHeight, true)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'$imageImagick->thumbnailImage']));
                }
                if (!$imageImagick->writeImage($path)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'$imageImagick->writeImage']));
                }
            }
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
