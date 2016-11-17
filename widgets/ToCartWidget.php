<?php

namespace app\widgets;

use yii\base\{ErrorExceptions,
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
     * @var string имя шаблона
     */
    public $view;
    /**
     * @var object ProductsModel для которого строится форма
     */
    private $model;
    
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
    
    public function setModel(ProductsModel $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
