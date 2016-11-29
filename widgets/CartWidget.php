<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\helpers\HashHelper;
use app\models\CurrencyModel;
use app\collections\CollectionInterface;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object CollectionInterface
     */
    private $purchasesCollection;
   /**
     * @var object Model
     */
    private $currencyModel;
    /**
     * @var string имя шаблона
     */
    public $view;
    /**
     * @var int общее количество товаров в корзине
     */
    private $goods = 0;
    /**
     * @var int общая стоимость товаров в корзине
     */
    private $cost = 0;
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->purchasesCollection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('purchasesCollection'));
            }
            if (empty($this->currencyModel)) {
                throw new ErrorException(ExceptionsTrait::emptyError('currencyModel'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
            
            if (!empty($this->purchasesCollection)) {
                $this->goods = $this->purchasesCollection->totalQuantity();
                $this->cost = $this->purchasesCollection->totalPrice();
            }
            
            $this->cost = \Yii::$app->formatter->asDecimal($this->cost * $this->currencyModel->exchange_rate, 2) . ' ' . $this->currencyModel->code;
            
            return $this->render($this->view, ['goods'=>$this->goods, 'cost'=>$this->cost]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству CartWidget::purchasesCollection
     * @param object $collection CollectionInterface
     */
    public function setPurchasesCollection(CollectionInterface $collection)
    {
        try {
            $this->purchasesCollection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству CartWidget::currencyModel
     * @param object $model Model
     */
    public function setCurrencyModel(Model $model)
    {
        try {
            $this->currencyModel = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
