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
class ModShortCartWidget extends AbstractBaseWidget
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
            
            $renderArray['goods'] = $this->goods;
            $renderArray['bagText'] = \Yii::t('base', 'Cart');
            $renderArray['cost'] = \Yii::$app->formatter->asDecimal($this->cost * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->symbol();
            $renderArray['toCartHref'] = Url::to(['/cart/index']);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ModShortCartWidget::purchases
     * @param PurchasesCollectionInterface $collection
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
     * Присваивает значение ModShortCartWidget::currency
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
     * Присваивает значение ModShortCartWidget::template
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
