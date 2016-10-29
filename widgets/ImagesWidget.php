<?php

namespace app\widgets;

use yii\base\{ErrorExceptions,
    Widget};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с тегами img, 
 * содержащими ссылки на изображения товара
 */
class ImagesWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя директории с изображениями
     */
    public $path = '';
    /**
     * @var string результирующая HTML строка
     */
    private $_result = '';
    
    public function run()
    {
        try {
            if (!empty($this->path)) {
                $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $this->path) . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                
                if (!empty($imagesArray)) {
                    foreach ($imagesArray as $image) {
                        $position = strpos(basename($image), 'thumbn_');
                        if ($position === false || $position > 0) {
                            $this->_result .= '<br><img src="' . \Yii::getAlias('@imagesweb/' . $this->path . '/') . basename($image) . '">';
                        }
                    }
                }
            }
            
            return $this->_result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
