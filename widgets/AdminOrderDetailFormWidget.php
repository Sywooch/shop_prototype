<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\{CurrencyInterface,
    PurchasesModel};
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
     * @var array
     */
    private $colors;
    /**
     * @var array
     */
    private $sizes;
    /**
     * @var array
     */
    private $deliveries;
    /**
     * @var array
     */
    private $payments;
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
            if (empty($this->statuses)) {
                throw new ErrorException($this->emptyError('statuses'));
            }
            if (empty($this->colors)) {
                throw new ErrorException($this->emptyError('colors'));
            }
            if (empty($this->sizes)) {
                throw new ErrorException($this->emptyError('sizes'));
            }
            if (empty($this->deliveries)) {
                throw new ErrorException($this->emptyError('deliveries'));
            }
            if (empty($this->payments)) {
                throw new ErrorException($this->emptyError('payments'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['id'] = $this->purchase->id;
            $renderArray['link'] = Url::to(['/product-detail/index', 'seocode'=>$this->purchase->product->seocode], true);
            $renderArray['linkText'] = Html::encode($this->purchase->product->name);
            $renderArray['short_description'] = Html::encode($this->purchase->product->short_description);
            $renderArray['date'] = \Yii::$app->formatter->asDate($this->purchase->received_date);
            $renderArray['price'] = \Yii::$app->formatter->asDecimal($this->purchase->price * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code();
            $renderArray['email'] = $this->purchase->email->email;
            if (!empty($this->purchase->product->images)) {
                $renderArray['image'] = ImgHelper::randThumbn($this->purchase->product->images);
            }
            
            if ((bool) $this->purchase->shipped === true) {
                $currentStatus = 'shipped';
            } elseif ((bool) $this->purchase->canceled === true) {
                $currentStatus = 'canceled';
            } elseif ((bool) $this->purchase->processed === true) {
                $currentStatus = 'processed';
            } elseif ((bool) $this->purchase->received === true) {
                $currentStatus = 'received';
            }
            
            $renderArray['modelForm'] = \Yii::configure($this->form, [
                'id'=>$this->purchase->id, 
                'name'=>$this->purchase->name->name,
                'surname'=>$this->purchase->surname->surname,
                'phone'=>$this->purchase->phone->phone,
                'address'=>$this->purchase->address->address,
                'city'=>$this->purchase->city->city,
                'country'=>$this->purchase->country->country,
                'postcode'=>$this->purchase->postcode->postcode,
                'quantity'=>$this->purchase->quantity,
                'id_color'=>$this->purchase->id_color,
                'id_size'=>$this->purchase->id_size,
                'id_delivery'=>$this->purchase->id_delivery,
                'id_payment'=>$this->purchase->id_payment,
                'status'=>$currentStatus,
            ]);
            
            $renderArray['formId'] = sprintf('admin-order-detail-form-%d', $this->purchase->id);
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/admin/order-change']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            
            $renderArray['statuses'] = $this->statuses;
            $renderArray['colors'] = $this->colors;
            $renderArray['sizes'] = $this->sizes;
            $renderArray['deliveries'] = $this->deliveries;
            $renderArray['payments'] = $this->payments;
            
            $renderArray['idHeader'] = \Yii::t('base', 'Order number');
            $renderArray['dateHeader'] = \Yii::t('base', 'Order date');
            $renderArray['priceHeader'] = \Yii::t('base', 'Price');
            $renderArray['emailHeader'] = 'Email';
            
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
     * Присваивает array свойству AdminOrderDetailFormWidget::colors
     * @param array $colors
     */
    public function setColors(array $colors)
    {
        try {
            $this->colors = $colors;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminOrderDetailFormWidget::sizes
     * @param array $sizes
     */
    public function setSizes(array $sizes)
    {
        try {
            $this->sizes = $sizes;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminOrderDetailFormWidget::deliveries
     * @param array $deliveries
     */
    public function setDeliveries(array $deliveries)
    {
        try {
            $this->deliveries = $deliveries;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminOrderDetailFormWidget::payments
     * @param array $payments
     */
    public function setPayments(array $payments)
    {
        try {
            $this->payments = $payments;
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
