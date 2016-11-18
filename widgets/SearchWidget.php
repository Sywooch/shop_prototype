<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с формой поиска
 */
class SearchWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует HTML строку с формой поиска
     * @return string
     */
    public function run()
    {
        try {
            $text = \Yii::$app->request->get(\Yii::$app->params['searchKey']);
            
            return $this->render($this->view, ['text'=>!empty($text) ? $text : '']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
