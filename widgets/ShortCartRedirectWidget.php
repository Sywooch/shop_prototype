<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\collections\PurchasesCollectionInterface;
use app\models\CurrencyInterface;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class ShortCartRedirectWidget extends AbstractBaseWidget
{
    /**
     * @var object PurchasesCollectionInterface
     */
    private $purchases;
    /**
     * @var CurrencyInterface
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
            
            $renderArray = [];
            
            $this->cost = \Yii::$app->formatter->asDecimal($this->cost * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code();
            
            $renderArray['goodsText'] = \Yii::t('base', 'Products in cart: {goods}, Total cost: {cost}', ['goods'=>$this->goods, 'cost'=>$this->cost]);
            
            $renderArray['formId'] = 'clean-cart-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/cart/clean-redirect']);
            $renderArray['button'] = \Yii::t('base', 'To clean cart');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCollectionInterface свойству ShortCartRedirectWidget::purchases
     * @param object $collection PurchasesCollectionInterface
     */
    public function setPurchases(PurchasesCollectionInterface $collection)
    {
        try {
            $this->purchases = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyInterface свойству ShortCartRedirectWidget::currency
     * @param CurrencyInterface $model
     */
    public function setCurrency(CurrencyInterface $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
