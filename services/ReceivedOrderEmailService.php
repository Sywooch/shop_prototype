<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\helpers\MailHelper;
use app\widgets\EmailReceivedOrderWidget;
use app\collections\PurchasesCollectionInterface;
use app\forms\AbstractBaseForm;
use app\models\CurrencyInterface;

/**
 * Отправляет Email сообщение об удачной регистрации
 */
class ReceivedOrderEmailService extends AbstractBaseService
{
    /**
     * @var string email для отправки сообщения
     */
    private $email;
    /**
     * @var PurchasesCollectionInterface
     */
    private $ordersCollection;
    /**
     * @var AbstractBaseForm
     */
    private $customerInfoForm;
    /**
     * @var CurrencyInterface
     */
    private $currentCurrencyModel;
    
    /**
     * Обрабатывает запрос на отправку сообщения
     */
    public function get()
    {
        try {
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            if (empty($this->ordersCollection)) {
                throw new ErrorException($this->emptyError('ordersCollection'));
            }
            if (empty($this->customerInfoForm)) {
                throw new ErrorException($this->emptyError('customerInfoForm'));
            }
            if (empty($this->currentCurrencyModel)) {
                throw new ErrorException($this->emptyError('currentCurrencyModel'));
            }
            
            $html = EmailReceivedOrderWidget::widget([
                'purchases'=>$this->ordersCollection, 
                'form'=>$this->customerInfoForm,
                'currency'=>$this->currentCurrencyModel,
                'header'=>\Yii::t('base', 'Hello! This is information about your order!'),
                'template'=>'email-received-order-mail.twig'
            ]);
            
            $mailHelper = new MailHelper([
                [
                    'from'=>['admin@shop.com'=>'Shop.com'], 
                    //'to'=>$this->email,
                    'to'=>'timofey@localhost',
                    'subject'=>\Yii::t('base', 'Information about your order'),
                    'html'=>$html
                ]
            ]);
            $sent = $mailHelper->send();
            
            if ($sent !== 1) {
                throw new ErrorException($this->methodError('sendEmail'));
            }
            
            return $sent;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ReceivedOrderEmailService::email
     * @param string $email
     */
    public function setEmail(string $email)
    {
        try {
            $this->email = $email;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ReceivedOrderEmailService::ordersCollection
     * @param PurchasesCollectionInterface $email
     */
    public function setOrdersCollection(PurchasesCollectionInterface $ordersCollection)
    {
        try {
            $this->ordersCollection = $ordersCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ReceivedOrderEmailService::customerInfoForm
     * @param AbstractBaseForm $customerInfoForm
     */
    public function setCustomerInfoForm(AbstractBaseForm $customerInfoForm)
    {
        try {
            $this->customerInfoForm = $customerInfoForm;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ReceivedOrderEmailService::customerInfoForm
     * @param CurrencyInterface $currentCurrencyModel
     */
    public function setCurrentCurrencyModel(CurrencyInterface $currentCurrencyModel)
    {
        try {
            $this->currentCurrencyModel = $currentCurrencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
