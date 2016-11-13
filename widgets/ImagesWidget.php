<?php

namespace app\widgets;

use yii\base\{ErrorExceptions,
    Widget};
use yii\helpers\Html;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с тегами img
 */
class ImagesWidget extends Widget
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
    /**
     * @var array массив тегов img
     */
    private $_result = [];
    
    public function run()
    {
        try {
            if (!empty($this->path)) {
                $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $this->path) . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                
                if (!empty($imagesArray)) {
                    foreach ($imagesArray as $image) {
                        if (preg_match('/^(?!thumbn_).+$/', basename($image)) === 1) {
                            $this->_result[] = Html::img(\Yii::getAlias('@imagesweb/' . $this->path . '/') . basename($image));
                        }
                    }
                }
            }
            
            return $this->render($this->view, ['images'=>!empty($this->_result) ? implode('<br/>', $this->_result) : '']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
