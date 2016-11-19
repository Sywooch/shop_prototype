<?php

namespace app\widgets;

use yii\base\{ErrorExceptions,
    Model,
    Widget};
use yii\helpers\{ArrayHelper,
    Html};
use app\exceptions\ExceptionsTrait;
use app\models\{ProductsModel,
    PurchasesModel};

/**
 * Формирует HTML строку с тегами img
 */
class ToCartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object ActiveRecord/Model
     */
    private $model;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $renderArray = [];
            
            $renderArray['purchase'] = new PurchasesModel(['quantity'=>1]);
            $renderArray['product'] = $this->model;
            $renderArray['colors'] = ArrayHelper::map($this->model->colors, 'id', 'color');
            $renderArray['sizes'] = ArrayHelper::map($this->model->sizes, 'id', 'size');
            
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
}
