<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Коллекция методов для обработки изображений
 */
class PicturesHelper
{
    use ExceptionsTrait;
    
    /**
     * @var object экземпляр Imagick в текущей итерации
     */
    private static $_objectImagick = null;
    /**
     * @var array массив путей к загружаемым изображениям, ключи:
     * thumbnails - массив путей к эскизам изображений
     * fullpath - массив путей к полноразмерным изображениям
     */
    private static $_pathImagesArray = array();
    
    /**
     * Обходит массив объектов, вызывая для каждого PicturesHelper::process()
     * @param array $objectsArray массив объектов yii\web\UploadedFile
     * @return boolean
     */
    public static function createPictures(Array $objectsArray)
    {
        try {
            foreach ($objectsArray as $objImg) {
                self::$_objectImagick = new \Imagick($objImg->tempName);
                if (!self::process(\Yii::$app->params['maxWidth'], \Yii::$app->params['maxHeight'], $objImg->tempName)) {
                    throw new ErrorException('Ошибка при обработке изображения!');
                }
                self::$_objectImagick = null;
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
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
                throw new ErrorException('Каталог ' . $path . ' не существует!');
            }
            if (empty($imgArray = glob($path . '/*.{jpg,png,gif}', GLOB_BRACE))) {
                return false;
            }
            foreach ($imgArray as $imgPath) {
                self::$_objectImagick = new \Imagick($imgPath);
                if (!$thumbnailPath = self::getThumbnailsPath($imgPath)) {
                    throw new ErrorException('Ошибка при создании имени эскиза!');
                }
                if (!self::process(\Yii::$app->params['maxThumbnailWidth'], \Yii::$app->params['maxThumbnailHeight'], $thumbnailPath)) {
                    throw new ErrorException('Ошибка при создании эскиза изображения!');
                }
                self::$_objectImagick = null;
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
     /**
     * Возвращает массив путей к изображениям в указанном каталоге
     * @param string $basePath путь к базовому каталогу
     * @param string $catalogName имя каталога, из которого необходимо загрузить изображения
     * @return array 
     */
    public static function getPathImages($basePath, $catalogName)
    {
        try {
            $catalogPath = \Yii::getAlias($basePath . '/' . $catalogName);
            if (!file_exists($catalogPath) || !is_dir($catalogPath)) {
                return false;
            }
            $imagesArray = glob($catalogPath . '/*.{jpg,png,gif}', GLOB_BRACE);
            if (!is_array($imagesArray) || empty($imagesArray)) {
                return false;
            }
            foreach ($imagesArray as $imgPath) {
                $webPath = \Yii::getAlias('@wpic/' . $catalogName . '/' . basename($imgPath));
                if (strpos($imgPath, \Yii::$app->params['thumbnailsPrefix'])) {
                    self::$_pathImagesArray[\Yii::$app->params['thumbnails']][] = $webPath;
                    continue;
                }
                self::$_pathImagesArray[\Yii::$app->params['fullpath']][] = $webPath;
            }
            return self::$_pathImagesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        } finally {
            self::$_pathImagesArray = array();
        }
    }
    
    /**
     * Возвращает путь к полноразмерным изображениям
     * @param string $basePath путь к базовому каталогу
     * @param string $catalogName имя каталога, из которого необходимо загрузить изображения
     * @return array 
     */
    public static function getFullPaths($basePath, $catalogName)
    {
        try {
            $picturesArray = self::getPathImages($basePath, $catalogName);
            return $picturesArray[\Yii::$app->params['fullpath']];
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает путь к 1 случайно выбранному эскизу
     * @param string $basePath путь к базовому каталогу
     * @param string $catalogName имя каталога, из которого необходимо загрузить изображения
     * @return string 
     */
    public static function getOneThumbnail($basePath, $catalogName)
    {
        try {
            $picturesArray = self::getPathImages($basePath, $catalogName);
            $thumbnailsArray = $picturesArray[\Yii::$app->params['thumbnails']];
            return $thumbnailsArray[array_rand($thumbnailsArray, 1)];
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает путь к эскизам
     * @param string $basePath путь к базовому каталогу
     * @param string $catalogName имя каталога, из которого необходимо загрузить изображения
     * @return array 
     */
    public static function getAllThumbnails($basePath, $catalogName)
    {
        try {
            $picturesArray = self::getPathImages($basePath, $catalogName);
            return $picturesArray[\Yii::$app->params['thumbnails']];
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет директорию с файлами при удалении товара из БД
     * @param string $dirName имя директории с файлами
     * @return boolean
     */
    public static function deletePictures($dirName)
    {
        try {
            $catalogPath = \Yii::getAlias('@pic/' . $dirName);
            if (file_exists($catalogPath) && is_dir($catalogPath)) {
                if (!empty($imagesArray = glob($catalogPath . '/*.{jpg,png,gif}', GLOB_BRACE))) {
                    foreach ($imagesArray as $image) {
                        if (!unlink($image)) {
                            throw new ErrorException('Ошибка при удалении файла!');
                        }
                    }
                }
                if (!rmdir($catalogPath)) {
                    throw new ErrorException('Ошибка при удалении директории!');
                }
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Анализирует и обрабатывает изображение
     * @param string $maxWidth максимально допустимая ширина
     * @param string $maxHeight максимально допустимая высота
     * @param string $path путь сохранения обработанного изображения
     * @return boolean
     */
    private static function process($maxWidth, $maxHeight, $path)
    {
        try {
            $currentWidth = self::getWidth();
            $currentHeight = self::getHeight();
            if ($currentWidth > $maxWidth || $currentHeight > $maxHeight) {
                if (!self::$_objectImagick->thumbnailImage($maxWidth, $maxHeight, true)) {
                    throw new ErrorException('Ошибка при масштабировании изображения!');
                }
                if (!self::$_objectImagick->writeImage($path)) {
                    throw new ErrorException('Ошибка при сохранении изображения!');
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
     * Конструирует путь для эскизов изображений
     * @param string $path путь на основании которого будет создан путь для эскиза
     * @return string
     */
    private static function getThumbnailsPath($path)
    {
        try {
            $dirname = dirname($path);
            $filename = basename($path);
            return $dirname . '/' . \Yii::$app->params['thumbnailsPrefix'] . $filename;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
