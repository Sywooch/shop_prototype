<?php

namespace app\widgets;

use yii\base\{ErrorExceptions,
    Widget};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с тегом img, 
 * содержащим ссылку на миниатюру товара
 */
class ThumbnailsWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя директории с изображениями
     */
    public $path = '';
    
    public function run()
    {
        try {
            if (!empty($this->path)) {
                $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $this->path) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                
                if (!empty($imagesArray)) {
                    $this->path = '<img src="' . \Yii::getAlias('@imagesweb/' . $this->path . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]) . '">';
                }
            }
            
            return $this->path;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
