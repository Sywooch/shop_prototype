<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\collections\PurchasesCollection;
use app\forms\CustomerInfoForm;
use app\finders\{DeliveryIdFinder,
    PaymentIdFinder};
use app\models\CurrencyModel;

/**
 * Формирует HTML строку с информацией об успешной регистрации
 */
class EmailReceivedOrderWidget extends AbstractBaseWidget
{
    /**
     * @var PurchasesCollection
     */
    private $purchases;
    /**
     * @var CustomerInfoForm
     */
    private $form;
    /**
     * @var CurrencyModel
     */
    private $currency;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с информацией об успешной регистрации
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->purchases)) {
                throw new ErrorException($this->emptyError('purchases'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Hello! This is information about your order!');
            
            foreach ($this->purchases as $purchase) {
                $set = [];
                $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
                $set['linkText'] = Html::encode($purchase->product->name);
                $set['short_description'] = $purchase->product->short_description;
                $set['price'] = \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchange_rate, 2) . ' ' . $this->currency->code;
                $set['color'] = sprintf('%s: %s', \Yii::t('base', 'Color'), $purchase->color->color);
                $set['size'] = sprintf('%s: %s', \Yii::t('base', 'Size'), $purchase->size->size);
                $renderArray['collection'][] = $set;
            }
            
            $renderArray['headerDelivery'] = \Yii::t('base', 'Delivery address');
            
            $renderArray['name'] = sprintf('%s: %s', \Yii::t('base', 'Name'), $this->form->name);
            $renderArray['surname'] = sprintf('%s: %s', \Yii::t('base', 'Surname'), $this->form->surname);
            $renderArray['email'] = sprintf('%s: %s', 'Email', $this->form->email);
            $renderArray['phone'] = sprintf('%s: %s', \Yii::t('base', 'Phone'), $this->form->phone);
            $renderArray['address'] = sprintf('%s: %s', \Yii::t('base', 'Address'), $this->form->address);
            $renderArray['city'] = sprintf('%s: %s', \Yii::t('base', 'City'), $this->form->city);
            $renderArray['country'] = sprintf('%s: %s', \Yii::t('base', 'Country'), $this->form->country);
            $renderArray['postcode'] = sprintf('%s: %s', \Yii::t('base', 'Postcode'), $this->form->postcode);
            
            $finder = \Yii::$app->registry->get(DeliveryIdFinder::class, ['id'=>$this->form->id_delivery]);
            $deliveriesModel = $finder->find();
            $renderArray['delivery'] = sprintf('%s: %s', \Yii::t('base', 'Delivery'), $deliveriesModel->description);
            
            $finder = \Yii::$app->registry->get(PaymentIdFinder::class, ['id'=>$this->form->id_payment]);
            $paymentsModel = $finder->find();
            $renderArray['payment'] = sprintf('%s: %s', \Yii::t('base', 'Payment'), $paymentsModel->description);
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCollection свойству EmailReceivedOrderWidget::purchases
     * @param PurchasesCollection $purchases
     */
    public function setPurchases(PurchasesCollection $purchases)
    {
        try {
            $this->purchases = $purchases;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CustomerInfoForm свойству EmailReceivedOrderWidget::form
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
     * Присваивает CurrencyModel свойству EmailReceivedOrderWidget::currency
     * @param CurrencyModel $model
     */
    public function setCurrency(CurrencyModel $model)
    {
        try {
            $this->currency = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
