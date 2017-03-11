<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\{CurrencyInterface,
    PurchasesModel};
use app\forms\AdminChangeOrderForm;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с данными заказа
 */
class AdminOrderDataWidget extends AbstractBaseWidget
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
     * @var AdminChangeOrderForm
     */
    private $form;
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
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $set = [];
            $renderArray['id'] = $this->purchase->id;
            $renderArray['date'] = \Yii::$app->formatter->asDate($this->purchase->received_date);
            $renderArray['link'] = Url::to(['/product-detail/index', 'seocode'=>$this->purchase->product->seocode], true);
            $renderArray['linkText'] = $this->purchase->product->name;
            $renderArray['short_description'] = $this->purchase->product->short_description;
            $renderArray['quantity'] = $this->purchase->quantity;
            $renderArray['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($this->purchase->price * $this->currency->exchangeRate(), 2), $this->currency->code());
            $renderArray['totalPrice'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal(($this->purchase->price * $this->purchase->quantity) * $this->currency->exchangeRate(), 2), $this->currency->code());
            $renderArray['color'] = $this->purchase->color->color;
            $renderArray['size'] = $this->purchase->size->size;
            if (!empty($this->purchase->product->images)) {
                $renderArray['image'] = ImgHelper::randThumbn($this->purchase->product->images);
            }
            
            $renderArray['client'] = sprintf('%s %s', $this->purchase->name->name, $this->purchase->surname->surname);
            $renderArray['phone'] = $this->purchase->phone->phone;
            $renderArray['address'] = $this->purchase->address->address;
            $renderArray['city'] = $this->purchase->city->city;
            $renderArray['country'] = $this->purchase->country->country;
            $renderArray['postcode'] = $this->purchase->postcode->postcode;
            $renderArray['payment'] = $this->purchase->payment->description;
            $renderArray['delivery'] = $this->purchase->delivery->description;
            
            if ((bool) $this->purchase->shipped === true) {
                $renderArray['status'] = \Yii::t('base', 'Shipped');
            } elseif ((bool) $this->purchase->canceled === true) {
                $renderArray['status'] = \Yii::t('base', 'Canceled');
            } elseif ((bool) $this->purchase->processed === true) {
                $renderArray['status'] = \Yii::t('base', 'Processed');
            } elseif ((bool) $this->purchase->received === true) {
                $renderArray['status'] = \Yii::t('base', 'Received');
            }
            
            $renderArray['modelForm'] = $this->form;
            $renderArray['formId'] = sprintf('admin-order-detail-get-form-%d', $this->purchase->id);
            $renderArray['formAction'] = Url::to(['/admin/order-detail-form']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            
            $renderArray['formSettings']['ajaxValidation'] = false;
            $renderArray['formSettings']['validateOnSubmit'] = false;
            $renderArray['formSettings']['validateOnChange'] = false;
            $renderArray['formSettings']['validateOnBlur'] = false;
            $renderArray['formSettings']['validateOnType'] = false;
            
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
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesModel свойству AdminOrderDataWidget::purchase
     * @param array $purchase
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
     * Присваивает CurrencyInterface свойству AdminOrderDataWidget::currency
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
     * Присваивает AdminChangeOrderForm свойству AdminOrderDataWidget::form
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
     * Присваивает имя шаблона свойству AdminOrderDataWidget::template
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
