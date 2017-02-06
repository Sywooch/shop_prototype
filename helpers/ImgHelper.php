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
                $image = Html::img(\Yii::getAlias(sprintf('@imagesweb/%s/%s', $directory, basename($imagesArray[random_int(0, count($imagesArray) - 1)]))), ['height'=>$height]);
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
                    $imgArray[] = Html::img(\Yii::getAlias(sprintf('@imagesweb/%s/%s', $directory, basename($img))), ['height'=>$height]);
                }
            }
            
            return implode('', $imgArray);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
