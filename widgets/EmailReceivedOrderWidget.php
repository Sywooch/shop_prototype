<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\collections\PurchasesCollectionInterface;
use app\forms\{AbstractBaseForm,
    CustomerInfoForm};
use app\finders\{DeliveryIdFinder,
    PaymentIdFinder};
use app\models\CurrencyInterface;

/**
 * Формирует HTML строку с информацией об успешной регистрации
 */
class EmailReceivedOrderWidget extends AbstractBaseWidget
{
    /**
     * @var PurchasesCollectionInterface
     */
    private $purchases;
    /**
     * @var AbstractBaseForm
     */
    private $form;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
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
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            foreach ($this->purchases as $purchase) {
                $set = [];
                $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
                $set['linkText'] = Html::encode($purchase->product->name);
                $set['short_description'] = $purchase->product->short_description;
                $set['quantity'] = sprintf('%s: %s', \Yii::t('base', 'Quantity'), $purchase->quantity);
                $set['price'] = sprintf('%s: %s', \Yii::t('base', 'Price'), \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code());
                $set['color'] = sprintf('%s: %s', \Yii::t('base', 'Color'), $purchase->color->color);
                $set['size'] = sprintf('%s: %s', \Yii::t('base', 'Size'), $purchase->size->size);
                $renderArray['collection'][] = $set;
            }
            
            $renderArray['summary'] = \Yii::t('base', 'Total number of items: {goods}, Total cost: {cost}', ['goods'=>$this->purchases->totalQuantity(), 'cost'=>\Yii::$app->formatter->asDecimal($this->purchases->totalPrice() * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code()]);
            
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
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCollectionInterface свойству EmailReceivedOrderWidget::purchases
     * @param PurchasesCollectionInterface $purchases
     */
    public function setPurchases(PurchasesCollectionInterface $purchases)
    {
        try {
            $this->purchases = $purchases;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает AbstractBaseForm свойству EmailReceivedOrderWidget::form
     * @param AbstractBaseForm $form
     */
    public function setForm(AbstractBaseForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyInterface свойству EmailReceivedOrderWidget::currency
     * @param CurrencyInterface $model
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
     * Присваивает заголовок свойству EmailReceivedOrderWidget::header
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
     * Присваивает имя шаблона свойству EmailReceivedOrderWidget::template
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
