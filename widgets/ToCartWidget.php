<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use app\exceptions\ExceptionsTrait;
use yii\helpers\ArrayHelper;

/**
 * Формирует HTML строку с тегами img
 */
class ToCartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object Model
     */
    private $model;
    /**
     * @var object Model, форма для получения данных из формы
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->model)) {
                throw new ErrorException($this->emptyError('model'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['formModel'] = $this->form;
            $renderArray['product'] = $this->model;
            
            $colors = $this->model->colors;
            ArrayHelper::multisort($colors, 'color');
            $renderArray['colors'] = ArrayHelper::getColumn($colors, 'color');
            
            $sizes = $this->model->sizes;
            ArrayHelper::multisort($sizes, 'size');
            $renderArray['sizes'] = ArrayHelper::getColumn($sizes, 'size');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству ToCartWidget::model
     * @param object $model Model
     */
    public function setModel(Model $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству ToCartWidget::form
     * @param object $model Model
     */
    public function setForm(Model $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
