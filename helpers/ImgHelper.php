<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\helpers\Html;
use app\exceptions\ExceptionsTrait;

/**
 * Коллекция методов для работы с изображениями
 */
class ImgHelper
{
    /**
     * Возвращает строку с тегом случайно выбранной миниатюры изображения 
     * @param string $directory имя каталога с изображениями
     * @param int $height высота результирующего изображения
     * @return string
     */
    public static function randThumbn(string $directory, int $height=200): string
    {
        try {
            $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $directory) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            
            if (!empty($imagesArray)) {
                $image = Html::img(\Yii::getAlias('@imagesweb/' . $directory . '/' . basename($imagesArray[random_int(0, count($imagesArray) - 1)])), ['height'=>$height]);
            }
            
            return $image ?? '';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает строку с тегами миниатюр изображений
     * @param string $directory имя каталога с изображениями
     * @param int $height высота результирующего изображения
     * @return string
     */
    public static function allThumbn(string $directory, int $height=50): string
    {
        try {
            $rawImgArray = glob(\Yii::getAlias('@imagesroot/' . $directory) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            
            $imgArray = [];
            
            if (!empty($rawImgArray)) {
                foreach ($rawImgArray as $img) {
                    $imgArray[] = Html::img(\Yii::getAlias('@imagesweb/' . $directory . '/' . basename($img)), ['height'=>$height]);
                }
            }
            
            return implode('', $imgArray);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Загружает изображения, возвращая имя каталога, 
     * в который они были загружены
     * @param array $uploadedFiles массив UploadedFile
     * @return mixed
     */
    public static function saveImages(array $uploadedFiles)
    {
        try {
            if (empty($uploadedFiles)) {
                throw new ErrorException(ExceptionsTrait::staticEmptyError('uploadedFiles'));
            }
            
            $catalog = time();
            $path = \Yii::getAlias('@imagesroot/' . $catalog);
            
            if (mkdir($path) === false) {
                throw new ErrorException(ExceptionsTrait::staticMethodError('mkdir'));
            }
            
            foreach ($uploadedFiles as $file) {
                $result = $file->saveAs($path . '/' . $file->getBaseName() . '.' . $file->getExtension());
                if ($result === false) {
                    throw new ErrorException(ExceptionsTrait::staticMethodError('saveAs'));
                }
            }
            
            self::makeThumbn($catalog);
            
            return $catalog ?? null;
        } catch (\Throwable $t) {
            self::removeImages($catalog);
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Создает миниатюры изображений
     * @param string $catalog имя каталога
     */
    public static function makeThumbn(string $catalog)
    {
        try {
            if (empty($catalog)) {
                throw new ErrorException(ExceptionsTrait::staticEmptyError('catalog'));
            }
            
            $maxThumbnailWidth = \Yii::$app->params['maxThumbnailWidth'];
            $maxThumbnailHeight = \Yii::$app->params['maxThumbnailHeight'];
            
            $path = \Yii::getAlias('@imagesroot/' . $catalog);
            $filesArray = glob($path . '/*.{jpg,png,gif}', GLOB_BRACE);
            
            if (!empty($filesArray)) {
                foreach ($filesArray as $file) {
                    $imagick = new \Imagick($file);
                    if ($imagick->getImageWidth() > $maxThumbnailWidth || $imagick->getImageHeight() > $maxThumbnailHeight) {
                        $imagick->thumbnailImage($maxThumbnailWidth, $maxThumbnailHeight, true);
                    }
                    $newPath = $path . '/' . \Yii::$app->params['thumbnailPrefix'] . basename($file);
                    $newFile = fopen($newPath, 'w');
                    $imagick->writeImageFile($newFile);
                    fclose($newFile);
                }
            }
            
            return true;
        } catch (\Throwable $t) {
            self::removeImages($catalog);
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет изображения и каталог
     * @param string $catalog имя каталога
     * @return bool
     */
    public static function removeImages(string $catalog)
    {
        try {
            if (!empty($catalog)) {
                $path = \Yii::getAlias('@imagesroot/' . $catalog);
                
                if (file_exists($path) && is_dir($path)) {
                    $filesArray = glob($path . '/*.{jpg,png,gif}', GLOB_BRACE);
                    
                    if (!empty($filesArray)) {
                        foreach ($filesArray as $file) {
                            unlink($file);
                        }
                    }
                    
                    rmdir($path);
                }
            }
            
            return true;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
