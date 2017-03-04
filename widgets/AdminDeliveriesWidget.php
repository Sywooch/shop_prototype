<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;
use app\models\CurrencyInterface;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminDeliveriesWidget extends AbstractBaseWidget
{
    /**
     * @var array DeliveriesModel
     */
    private $deliveries;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var AbstractBaseForm
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
            if (empty($this->deliveries)) {
                throw new ErrorException($this->emptyError('deliveries'));
            }
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
            
            $deliveriesArray = [];
            foreach ($this->deliveries as $delivery) {
                $set = [];
                $set['name'] = $delivery->name;
                $set['description'] = $delivery->description;
                $set['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($delivery->price * $this->currency->exchangeRate(), 2), $this->currency->code());
                $set['active'] = $delivery->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
                
                $deliveriesForm = clone $this->form;
                $set['modelForm'] = \Yii::configure($deliveriesForm, ['id'=>$delivery->id]);
                
                $set['formId'] = sprintf('admin-delivery-get-form-%d', $delivery->id);
                $set['formAction'] = Url::to(['/admin/delivery-form']);
                $set['button'] = \Yii::t('base', 'Change');
                
                $set['formIdDelete'] = sprintf('admin-delivery-delete-form-%d', $delivery->id);
                $set['formActionDelete'] = Url::to(['/admin/delivery-delete']);
                $set['buttonDelete'] = \Yii::t('base', 'Delete');
                
                $set['ajaxValidation'] = false;
                $set['validateOnSubmit'] = false;
                $set['validateOnChange'] = false;
                $set['validateOnBlur'] = false;
                $set['validateOnType'] = false;
                
                $deliveriesArray[] = $set;
            }
            
            ArrayHelper::multisort($deliveriesArray, 'name', SORT_ASC);
            $renderArray['deliveries'] = $deliveriesArray;
            
            $renderArray['nameHeader'] = \Yii::t('base', 'Name');
            $renderArray['descriptionHeader'] = \Yii::t('base', 'Description');
            $renderArray['priceHeader'] = \Yii::t('base', 'Price');
            $renderArray['activeHeader'] = \Yii::t('base', 'Active');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminDeliveriesWidget::deliveries
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
     * Присваивает значение AdminDeliveriesWidget::currency
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
     * Присваивает значение AdminDeliveriesWidget::form
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
     * Присваивает значение AdminDeliveriesWidget::header
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
     * Присваивает значение AdminDeliveriesWidget::template
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
