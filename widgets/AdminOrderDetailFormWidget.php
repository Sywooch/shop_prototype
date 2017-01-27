<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
use app\forms\AdminChangeOrderForm;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminOrderDetailFormWidget extends AbstractBaseWidget
{
    /**
     * @var PurchasesModel
     */
    private $purchase;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var array
     */
    private $statuses;
    /**
     * @var AdminChangeOrderForm
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
            if (empty($this->purchase)) {
                throw new ErrorException($this->emptyError('purchase'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->statuses)) {
                throw new ErrorException($this->emptyError('statuses'));
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
            
            $renderArray['id'] = $purchase->id;
            $renderArray['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
            $renderArray['linkText'] = Html::encode($purchase->product->name);
            $renderArray['short_description'] = Html::encode($purchase->product->short_description);
            $renderArray['date'] = \Yii::$app->formatter->asDate($purchase->received_date);
            $renderArray['price'] = \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code();
            $renderArray['email'] = $purchase->email->email;
            if (!empty($purchase->product->images)) {
                $renderArray['image'] = ImgHelper::randThumbn($purchase->product->images);
            }
            
            $currentName = $purchase->name->name;
            $currentSurname = $purchase->surname->surname;
            $currentPhone = $purchase->phone->phone;
            $currentAddress = $purchase->address->address;
            $currentCity = $purchase->city->city;
            $currentCountry = $purchase->country->country;
            $currentPostcode = $purchase->postcode->postcode;
            $currentQuantity = $purchase->quantity;
            $currentColor = $purchase->id_color;
            $currentSize = $purchase->id_size;
            $currentDelivery = $purchase->id_delivery;
            $currentPayment = $purchase->id_payment;
            if ((bool) $purchase->shipped === true) {
                $currentStatus = 'shipped';
            } elseif ((bool) $purchase->canceled === true) {
                $currentStatus = 'canceled';
            } elseif ((bool) $purchase->processed === true) {
                $currentStatus = 'processed';
            } elseif ((bool) $purchase->received === true) {
                $currentStatus = 'received';
            }
            
            $renderArray['modelForm'] = \Yii::configure($this->form, [
                'id'=>$purchase->id, 
                'name'=>$currentName,
                'surname'=>$currentSurname,
                'phone'=>$currentPhone,
                'address'=>$currentAddress,
                'city'=>$currentCity,
                'country'=>$currentCountry,
                'postcode'=>$currentPostcode,
                'quantity'=>$currentQuantity,
                'color'=>$currentColor,
                'size'=>$currentSize,
                'delivery'=>$currentDelivery,
                'payment'=>$currentPayment,
                'status'=>$currentStatus,
            ]);
            
            $renderArray['formId'] = 'admin-order-detail-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/admin/orders-change-status']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            
            $renderArray['statuses'] = $this->statuses;
            
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
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesModel свойству AdminOrderDetailFormWidget::purchase
     * @param PurchasesModel $purchase
     */
    public function setPurchase(PurchasesModel $purchase)
    {
        try {
            $this->purchase = $purchase;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyInterface свойству AdminOrderDetailFormWidget::currency
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
     * Присваивает array свойству AdminOrderDetailFormWidget::statuses
     * @param array $statuses
     */
    public function setStatuses(array $statuses)
    {
        try {
            $this->statuses = $statuses;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает AdminChangeOrderForm свойству AdminOrderDetailFormWidget::form
     * @param AdminChangeOrderForm $form
     */
    public function setForm(AdminChangeOrderForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству AdminOrderDetailFormWidget::header
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
     * Присваивает имя шаблона свойству AdminOrderDetailFormWidget::template
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
