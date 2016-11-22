<?php

namespace app\widgets;

use yii\base\{ErrorException,
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
     * @var object ActiveRecord/Model, получает данные из формы
     */
    private $purchase;
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
            if (empty($this->purchase)) {
                throw new ErrorException(ExceptionsTrait::emptyError('purchase'));
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
            
            $renderArray['purchase'] = $this->purchase;
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
    
    /**
     * Присваивает Model свойству ToCartWidget::purchase
     * @param object $model Model
     */
    public function setPurchase(Model $model)
    {
        try {
            $this->purchase = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
