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
    /**
     * Обрабатывает переданный объект yii\web\UploadedFile
     * @param array $image объект yii\web\UploadedFile
     * @return bool
     */
    public static function resize(UploadedFile $image): bool
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
     * @return bool
     */
    public static function createThumbnails(string $path): bool
    {
        try {
            if (!file_exists($path) && !is_dir($path)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'$path']));
            }
            $imagesArray = glob($path . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            if (empty($imagesArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $imagesArray']));
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
     * @param int $maxWidth максимально допустимая ширина
     * @param int $maxHeight максимально допустимая высота
     * @param string $path путь сохранения обработанного изображения
     * @return bool
     */
    private static function process(\Imagick $imageImagick, int $maxWidth, int $maxHeight, string $path): bool
    {
        try {
            $currentWidth = $imageImagick->getImageWidth();
            $currentHeight = $imageImagick->getImageHeight();
            if ($currentWidth > $maxWidth || $currentHeight > $maxHeight) {
                $imageImagick->thumbnailImage($maxWidth, $maxHeight, true);
                $imageImagick->writeImage($path);
            }
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет директорию с изображениями
     * @param string $path путь к удаляемой директории
     */
    public static function remove(string $path): bool
    {
        try {
            if (file_exists($path) && is_dir($path)) {
                $imagesArray = glob($path . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                if (!empty($imagesArray)) {
                    foreach ($imagesArray as $image) {
                        if (!unlink($image)) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'unlink']));
                        }
                    }
                }
                if (!rmdir($path)) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'rmdir']));
                }
            }
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
