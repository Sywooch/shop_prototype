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
class ShortCartWidget extends AbstractBaseWidget
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
    private $template;
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
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            if ($this->purchases->isEmpty() === false) {
                $this->goods = $this->purchases->totalQuantity();
                $this->cost = $this->purchases->totalPrice();
            }
            
            $renderArray = [];
            
            $this->cost = \Yii::$app->formatter->asDecimal($this->cost * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code();
            
            $renderArray['extended'] = $this->goods > 0 ? true : false;
            
            $renderArray['goodsText'] = \Yii::t('base', 'Products in cart: {goods}, Total cost: {cost}', ['goods'=>$this->goods, 'cost'=>$this->cost]);
            $renderArray['toCartHref'] = Url::to(['/cart/index']);
            $renderArray['toCartText'] = \Yii::t('base', 'To cart');
            
            $renderArray['formId'] = 'clean-cart-form';
            $renderArray['formAction'] = Url::to(['/cart/clean']);
            $renderArray['button'] = \Yii::t('base', 'To clean cart');
            
            $renderArray['formSettings']['ajaxValidation'] = false;
            $renderArray['formSettings']['validateOnSubmit'] = false;
            $renderArray['formSettings']['validateOnChange'] = false;
            $renderArray['formSettings']['validateOnBlur'] = false;
            $renderArray['formSettings']['validateOnType'] = false;
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCollectionInterface свойству ShortCartWidget::purchases
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
     * Присваивает CurrencyInterface свойству ShortCartWidget::currency
     * @param CurrencyInterface $model
     */
    public function setCurrency(CurrencyInterface $model)
    {
        try {
            $this->currency = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству ShortCartWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
