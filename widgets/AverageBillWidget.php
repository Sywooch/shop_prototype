<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyModel;
use app\collections\PurchasesCollectionInterface;

/**
 * Формирует HTML строку с информацией о среднем чеке текущего дня
 */
class AverageBillWidget extends AbstractBaseWidget
{
    /**
     * @var PurchasesCollectionInterface
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
     * Конструирует HTML строку с информацией об отсутствии результатов поиска
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Average bill');
            
            if ($this->purchases->isEmpty() === false) {
                $bill = $this->purchases->totalPrice() / $this->purchases->count();
            }
            
            $renderArray['text'] = sprintf('%s: %s %s', \Yii::t('base', 'Average bill today'), \Yii::$app->formatter->asDecimal(($bill ?? 0) * $this->currency->exchange_rate, 2), $this->currency->code);
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCollectionInterface свойству AverageBillWidget::purchases
     * @param array $purchases
     */
    public function setPurchases(PurchasesCollectionInterface $purchases)
    {
        try {
            $this->purchases = $purchases;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyModel свойству AverageBillWidget::currency
     * @param CurrencyModel $currency
     */
    public function setCurrency(CurrencyModel $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
