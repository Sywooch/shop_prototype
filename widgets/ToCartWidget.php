<?php

namespace app\widgets;

use yii\base\{ErrorExceptions,
    Widget};
use yii\helpers\Html;
use app\exceptions\ExceptionsTrait;
use app\models\PurchasesModel;

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
     * @var object ProductsModel, для которого строится форма
     */
    public $product;
    
    public function run()
    {
        try {
            $purchase = new PurchasesModel(['quantity'=>1]);
            
            return $this->render($this->view, ['purchase'=>$purchase, 'product'=>$this->product]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
