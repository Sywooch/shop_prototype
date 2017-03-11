<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
use app\forms\AdminChangeOrderForm;
use app\helpers\ImgHelper;

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
     * @var CurrencyInterface
     */
    private $currency;
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
                $renderArray['listClass'] = 'admin-orders';
                
                foreach ($this->purchases as $purchase) {
                    $set = [];
                    $set['id'] = $purchase->id;
                    $set['date'] = \Yii::$app->formatter->asDate($purchase->received_date);
                    $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
                    $set['linkText'] = $purchase->product->name;
                    $set['short_description'] = $purchase->product->short_description;
                    $set['quantity'] = $purchase->quantity;
                    $set['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchangeRate(), 2), $this->currency->code());
                    $set['totalPrice'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal(($purchase->price * $purchase->quantity) * $this->currency->exchangeRate(), 2), $this->currency->code());
                    $set['color'] = $purchase->color->color;
                    $set['size'] = $purchase->size->size;
                    if (!empty($purchase->product->images)) {
                        $set['image'] = ImgHelper::randThumbn($purchase->product->images);
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
                    
                    $set['activeForm'] = true;
                    
                    $set['formId'] = sprintf('admin-order-detail-get-form-%d', $purchase->id);
                    
                    $renderArray['purchases'][] = $set;
                }
                
                $renderArray['modelForm'] = $this->form;
                
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
            } else {
                $renderArray['ordersEmpty'] = \Yii::t('base', 'No orders');
            }
            
            return $this->render($this->template, $renderArray);
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
     * Присваивает CurrencyInterface свойству AdminOrdersWidget::currency
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
     * Присваивает AdminChangeOrderForm свойству AdminOrdersWidget::form
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
     * Присваивает заголовок свойству AdminOrdersWidget::header
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
     * Присваивает имя шаблона свойству AdminOrdersWidget::template
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
