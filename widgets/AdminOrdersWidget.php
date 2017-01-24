<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyModel;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminOrdersWidget extends AbstractBaseWidget
{
    /**
     * @var array PurchasesModel
     */
    private $purchases;
    /**
     * @var CurrencyModel
     */
    private $currency;
    /**
     * @var string заголовок
     */
    public $header;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            if (!empty($this->purchases)) {
                ArrayHelper::multisort($this->purchases, 'received_date', SORT_DESC, SORT_REGULAR);
                
                foreach ($this->purchases as $purchase) {
                    $set = [];
                    $set['date'] = \Yii::$app->formatter->asDate($purchase->received_date);
                    $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
                    $set['linkText'] = Html::encode($purchase->product->name);
                    $set['short_description'] = Html::encode($purchase->product->short_description);
                    $set['quantity'] = $purchase->quantity;
                    $set['price'] = \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchange_rate, 2) . ' ' . $this->currency->code;
                    $set['color'] = $purchase->color->color;
                    $set['size'] = $purchase->size->size;
                    if (!empty($purchase->product->images)) {
                        $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $purchase->product->images) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                        if (!empty($imagesArray)) {
                            $set['image'] = Html::img(\Yii::getAlias('@imagesweb/' . $purchase->product->images . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]), ['height'=>200]);
                        }
                    }
                    
                    if ((bool) $purchase->shipped === true) {
                        $set['status'] = \Yii::t('base', 'Shipped');
                    } elseif ((bool) $purchase->canceled === true) {
                        $set['status'] = \Yii::t('base', 'Canceled');
                    } elseif ((bool) $purchase->processed === true) {
                        $set['status'] = \Yii::t('base', 'Processed');
                    } elseif ((bool) $purchase->received === true) {
                        $set['status'] = \Yii::t('base', 'Received');
                    }
                    
                    $renderArray['purchases'][] = $set;
                }
                
                $renderArray['dateHeader'] = \Yii::t('base', 'Order date');
                $renderArray['quantityHeader'] = \Yii::t('base', 'Quantity');
                $renderArray['priceHeader'] = \Yii::t('base', 'Price');
                $renderArray['colorHeader'] = \Yii::t('base', 'Color');
                $renderArray['sizeHeader'] = \Yii::t('base', 'Size');
                $renderArray['statusHeader'] = \Yii::t('base', 'Status');
            } else {
                $renderArray['purchasesEmpty'] = \Yii::t('base', 'Today no orders');
            }
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array PurchasesModel свойству AdminOrdersWidget::purchases
     * @param array $purchases
     */
    public function setPurchases(array $purchases)
    {
        try {
            $this->purchases = $purchases;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyModel свойству AdminOrdersWidget::currency
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
