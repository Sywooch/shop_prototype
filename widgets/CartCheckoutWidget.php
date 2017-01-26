<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\forms\CustomerInfoForm;
use app\models\CurrencyInterface;

/**
 * Формирует HTML строку с формой оформления заказа
 */
class CartCheckoutWidget extends AbstractBaseWidget
{
    /**
     * @var object CustomerInfoForm
     */
    private $form;
    /**
     * @var array DeliveriesModel
     */
    private $deliveries;
    /**
     * @var array PaymentsModel
     */
    private $payments;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->deliveries)) {
                throw new ErrorException($this->emptyError('deliveries'));
            }
            if (empty($this->payments)) {
                throw new ErrorException($this->emptyError('payments'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['headerCustomerInfo'] = \Yii::t('base', 'Contact Information');
            $renderArray['headerDeliveryInfo'] = \Yii::t('base', 'Delivery address');
            $renderArray['headerDeliveryTypeInfo'] = \Yii::t('base', 'Delivery type');
            $renderArray['headerPurchaseTypeInfo'] = \Yii::t('base', 'Method of payment');
            
            $renderArray['modelForm'] = $this->form;
            $renderArray['formId'] = 'cart-сheckout-ajax-form';
            
            $renderArray['ajaxValidation'] = true;
            $renderArray['validateOnSubmit'] = true;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            ArrayHelper::multisort($this->deliveries, 'description');
            foreach ($this->deliveries as $delivery) {
                $delivery->description .= sprintf(' %s', \Yii::$app->formatter->asDecimal($delivery->price * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code());
            }
            $renderArray['deliveries'] = ArrayHelper::map($this->deliveries, 'id', 'description');
            
            ArrayHelper::multisort($this->payments, 'description');
            $renderArray['payments'] = ArrayHelper::map($this->payments, 'id', 'description');
            
            $renderArray['readonly'] = \Yii::$app->user->isGuest === true ? false : true;
            $renderArray['isGuest'] = \Yii::$app->user->isGuest;
            
            $renderArray['formAction'] = Url::to(['/cart/сheckout-ajax']);
            $renderArray['button'] = \Yii::t('base', 'Send an order');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CustomerInfoForm свойству CartCheckoutWidget::form
     * @param CustomerInfoForm $form
     */
    public function setForm(CustomerInfoForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству CartCheckoutWidget::deliveries
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
     * Присваивает array свойству CartCheckoutWidget::payments
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
     * Присваивает CurrencyInterface свойству CartCheckoutWidget::currency
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
}
