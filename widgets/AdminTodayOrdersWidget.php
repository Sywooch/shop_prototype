<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminTodayOrdersWidget extends AbstractBaseWidget
{
    /**
     * @var array PurchasesModel
     */
    private $purchases;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
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
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            if (!empty($this->purchases)) {
                ArrayHelper::multisort($this->purchases, 'received_date', SORT_DESC, SORT_REGULAR);
                
                foreach ($this->purchases as $purchase) {
                    $set = [];
                    $set['id'] = $purchase->id;
                    $set['link'] = Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$purchase->product->seocode]);
                    $set['linkText'] = Html::encode($purchase->product->name);
                    if (!empty($purchase->product->images)) {
                        $set['image'] = ImgHelper::randThumbn($purchase->product->images);
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
                
                $renderArray['idHeader'] = \Yii::t('base', 'Order number');
                $renderArray['statusHeader'] = \Yii::t('base', 'Status');
            } else {
                $renderArray['purchasesEmpty'] = \Yii::t('base', 'Today no orders');
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array PurchasesModel свойству AdminTodayOrdersWidget::purchases
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
     * Присваивает CurrencyInterface свойству AdminTodayOrdersWidget::currency
     * @param CurrencyInterface $currency
     */
    public function setCurrency(CurrencyInterface $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству AdminTodayOrdersWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AdminTodayOrdersWidget::template
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
