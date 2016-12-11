<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Html;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с информацией об отсутствии результатов поиска
 */
class EmptyProductsWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с информацией об отсутствии результатов поиска
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $data = Html::tag('p', \Yii::t('base', 'Search by this parameters returned no results'));
            
            return $this->render($this->view, ['text'=>$data]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
