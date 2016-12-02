<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Html;
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
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->path)) {
                throw new ErrorException(ExceptionsTrait::emptyError('path'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
            
            $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $this->path) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            
            if (!empty($imagesArray)) {
                $result = Html::img(\Yii::getAlias('@imagesweb/' . $this->path . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]));
            }
            
            return $this->render($this->view, ['images'=>!empty($result) ? $result : '']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
