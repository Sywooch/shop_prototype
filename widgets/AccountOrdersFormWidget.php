<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
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
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var PurchaseForm
     */
    private $form;
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
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
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
                $renderArray['listClass'] = 'account-orders';
                $renderArray['statusClass'] = 'account-order-status';
                
                foreach ($this->purchases as $purchase) {
                    $set = [];
                    $set['id'] = $purchase->id;
                    $set['date'] = \Yii::$app->formatter->asDate($purchase->received_date);
                    $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
                    $set['linkText'] = Html::encode($purchase->product->name);
                    $set['short_description'] = Html::encode($purchase->product->short_description);
                    $set['quantity'] = $purchase->quantity;
                    $set['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchangeRate(), 2), $this->currency->code());
                    $set['totalPrice'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal(($purchase->price * $purchase->quantity) * $this->currency->exchangeRate(), 2), $this->currency->code());
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
                        $set['formAction'] = Url::to(['/account/order-cancel']);
                        $set['button'] = \Yii::t('base', 'Cancel');
                        
                        $set['ajaxValidation'] = false;
                        $set['validateOnSubmit'] = false;
                        $set['validateOnChange'] = false;
                        $set['validateOnBlur'] = false;
                        $set['validateOnType'] = false;
                    }
                    
                    $renderArray['purchases'][] = $set;
                }
                
                $renderArray['dateHeader'] = \Yii::t('base', 'Order date');
                $renderArray['idHeader'] = \Yii::t('base', 'Order number');
                $renderArray['quantityHeader'] = \Yii::t('base', 'Quantity');
                $renderArray['priceHeader'] = \Yii::t('base', 'Price');
                $renderArray['totalPriceHeader'] = \Yii::t('base', 'Total price');
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
            } else {
                $renderArray['ordersEmpty'] = \Yii::t('base', 'No orders');
            }
            
            return $this->render($this->template, $renderArray);
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
     * Присваивает CurrencyInterface свойству AccountOrdersFormWidget::currency
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
    
    /**
     * Присваивает заголовок свойству AccountOrdersFormWidget::header
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
     * Присваивает имя шаблона свойству AccountOrdersFormWidget::template
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
