<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\collections\PurchasesCollection;
use app\models\CurrencyModel;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object PurchasesCollection
     */
    private $purchases;
    /**
     * @var CurrencyModel
     */
    private $currency;
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
            if (empty($this->purchases)) {
                throw new ErrorException($this->emptyError('purchases'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            if ($this->purchases->isEmpty() === false) {
                $this->goods = $this->purchases->totalQuantity();
                $this->cost = $this->purchases->totalPrice();
            }
            
            $this->cost = \Yii::$app->formatter->asDecimal($this->cost * $this->currency->exchange_rate, 2) . ' ' . $this->currency->code;
            
            return $this->render($this->view, ['goods'=>$this->goods, 'cost'=>$this->cost]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCollection свойству CartWidget::purchases
     * @param object $collection PurchasesCollection
     */
    public function setPurchases(PurchasesCollection $collection)
    {
        try {
            $this->purchases = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyModel свойству ProductsListWidget::currency
     * @param CurrencyModel $model
     */
    public function setCurrency(CurrencyModel $model)
    {
        try {
            $this->currency = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
