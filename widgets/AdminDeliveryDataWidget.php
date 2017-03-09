<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;
use app\models\CurrencyInterface;

/**
 * Формирует HTML строку с данными
 */
class AdminDeliveryDataWidget extends AbstractBaseWidget
{
    /**
     * @var array Model
     */
    private $delivery;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var AbstractBaseForm
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
            if (empty($this->delivery)) {
                throw new ErrorException($this->emptyError('delivery'));
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
            
            $renderArray['id'] = $this->delivery->id;
            $renderArray['name'] = $this->delivery->name;
            $renderArray['description'] = $this->delivery->description;
            $renderArray['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($this->delivery->price * $this->currency->exchangeRate(), 2), $this->currency->code());
            $renderArray['active'] = $this->delivery->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['formIdChange'] = sprintf('admin-delivery-get-form-%d', $this->delivery->id);
            $renderArray['formActionChange'] = Url::to(['/admin/delivery-form']);
            $renderArray['buttonChange'] = \Yii::t('base', 'Change');
            
            $renderArray['formIdDelete'] = sprintf('admin-delivery-delete-form-%d', $this->delivery->id);
            $renderArray['formActionDelete'] = Url::to(['/admin/delivery-delete']);
            $renderArray['buttonDelete'] = \Yii::t('base', 'Delete');
            
            $renderArray['formSettings']['ajaxValidation'] = false;
            $renderArray['formSettings']['validateOnSubmit'] = false;
            $renderArray['formSettings']['validateOnChange'] = false;
            $renderArray['formSettings']['validateOnBlur'] = false;
            $renderArray['formSettings']['validateOnType'] = false;
            
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
     * Присваивает значение AdminDeliveryDataWidget::delivery
     * @param Model $delivery
     */
    public function setDelivery(Model $delivery)
    {
        try {
            $this->delivery = $delivery;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminDeliveryDataWidget::currency
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
     * Присваивает значение AdminDeliveryDataWidget::form
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
     * Присваивает значение AdminDeliveryDataWidget::template
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
