<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
use app\forms\OrderStatusForm;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminOrdersFormWidget extends AbstractBaseWidget
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
     * @var array
     */
    private $statuses;
    /**
     * @var OrderStatusForm
     */
    private $form;
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
            if (empty($this->statuses)) {
                throw new ErrorException($this->emptyError('statuses'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
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
                $renderArray['listClass'] = 'admin-orders';
                
                foreach ($this->purchases as $purchase) {
                    $set = [];
                    $set['orderId'] = sprintf('admin-order-%d', $purchase->id);
                    $set['date'] = \Yii::$app->formatter->asDate($purchase->received_date);
                    $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
                    $set['linkText'] = Html::encode($purchase->product->name);
                    $set['short_description'] = Html::encode($purchase->product->short_description);
                    $set['quantity'] = $purchase->quantity;
                    $set['price'] = \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code();
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
                        $status = 'shipped';
                    } elseif ((bool) $purchase->canceled === true) {
                        $status = 'canceled';
                    } elseif ((bool) $purchase->processed === true) {
                        $status = 'processed';
                    } elseif ((bool) $purchase->received === true) {
                        $status = 'received';
                    }
                    
                    $form = clone $this->form;
                    $set['modelForm'] = \Yii::configure($form, ['id'=>$purchase->id, 'status'=>$status]);
                    $set['formId'] = sprintf('order-status-form-%d', $purchase->id);
                    
                    $set['ajaxValidation'] = false;
                    $set['validateOnSubmit'] = false;
                    $set['validateOnChange'] = false;
                    $set['validateOnBlur'] = false;
                    $set['validateOnType'] = false;
                    
                    $set['formAction'] = Url::to(['/admin/order-change']);
                    //$set['button'] = \Yii::t('base', 'Change');
                    
                    $renderArray['purchases'][] = $set;
                }
                
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
            } else {
                $renderArray['ordersEmpty'] = \Yii::t('base', 'No orders');
            }
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array PurchasesModel свойству AdminOrdersFormWidget::purchases
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
     * Присваивает CurrencyInterface свойству AdminOrdersFormWidget::currency
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
     * Присваивает array свойству AdminOrdersFormWidget::statuses
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
     * Присваивает OrderStatusForm свойству AdminOrdersFormWidget::form
     * @param OrderStatusForm $form
     */
    public function setForm(OrderStatusForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
