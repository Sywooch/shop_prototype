<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyModel;
use app\forms\PurchaseForm;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AccountOrdersFormWidget extends AbstractBaseWidget
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
     * @var PurchaseForm
     */
    private $form;
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
            if (empty($this->purchases)) {
                throw new ErrorException($this->emptyError('purchases'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            if (!empty($this->purchases)) {
                $renderArray['userOrders'] = \Yii::t('base', 'Orders');
                
                $renderArray['listClass'] = 'account-orders';
                $renderArray['statusClass'] = 'account-order-status';
                
                ArrayHelper::multisort($this->purchases, 'received_date', SORT_DESC, SORT_REGULAR);
                
                foreach ($this->purchases as $purchase) {
                    $set = [];
                    $set['orderId'] = sprintf('account-order-%d', $purchase->id);
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
                    
                    $set['client'] = sprintf('%s %s', $purchase->name->name, $purchase->surname->surname);
                    $set['phone'] = $purchase->phone->phone;
                    $set['address'] = $purchase->address->address;
                    $set['city'] = $purchase->city->city;
                    $set['country'] = $purchase->country->country;
                    $set['postcode'] = $purchase->postcode->postcode;
                    $set['payment'] = $purchase->payment->description;
                    $set['delivery'] = $purchase->delivery->description;
                    
                    if ((bool) $purchase->shipped === true) {
                        $set['status'] = \Yii::t('base', 'Shipped');
                    } elseif ((bool) $purchase->canceled === true) {
                        $set['status'] = \Yii::t('base', 'Canceled');
                    } elseif ((bool) $purchase->processed === true) {
                        $set['status'] = \Yii::t('base', 'Processed');
                    } elseif ((bool) $purchase->received === true) {
                        $set['status'] = \Yii::t('base', 'Received');
                    }
                    
                    if ((bool) $purchase->shipped !== true && (bool) $purchase->canceled !== true) {
                        $form = clone $this->form;
                        $set['modelForm'] = \Yii::configure($form, ['id'=>$purchase->id]);
                        $set['formId'] = sprintf('order-cancellation-form-%d', $purchase->id);
                        
                        $set['ajaxValidation'] = false;
                        $set['validateOnSubmit'] = false;
                        $set['validateOnChange'] = false;
                        $set['validateOnBlur'] = false;
                        $set['validateOnType'] = false;
                        
                        $set['formAction'] = Url::to(['/account/order-cancel']);
                        $set['button'] = \Yii::t('base', 'Cancel');
                    }
                    
                    $renderArray['purchases'][] = $set;
                }
                
                $renderArray['dateHeader'] = \Yii::t('base', 'Order date');
                $renderArray['quantityHeader'] = \Yii::t('base', 'Quantity');
                $renderArray['priceHeader'] = \Yii::t('base', 'Price');
                $renderArray['colorHeader'] = \Yii::t('base', 'Color');
                $renderArray['sizeHeader'] = \Yii::t('base', 'Size');
                $renderArray['statusHeader'] = \Yii::t('base', 'Status');
                
                $renderArray['clientHeader'] = \Yii::t('base', 'Client');
                $renderArray['phoneHeader'] = \Yii::t('base', 'Phone');
                $renderArray['addressHeader'] = \Yii::t('base', 'Address');
                $renderArray['cityHeader'] = \Yii::t('base', 'City');
                $renderArray['countryHeader'] = \Yii::t('base', 'Country');
                $renderArray['postcodeHeader'] = \Yii::t('base', 'Postcode');
                $renderArray['paymentHeader'] = \Yii::t('base', 'Payment');
                $renderArray['deliveryHeader'] = \Yii::t('base', 'Delivery');
            }
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array PurchasesModel свойству AccountOrdersFormWidget::purchases
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
     * Присваивает CurrencyModel свойству AccountOrdersFormWidget::currency
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
    
    /**
     * Присваивает PurchaseForm свойству AccountOrdersFormWidget::form
     * @param PurchaseForm $form
     */
    public function setForm(PurchaseForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}