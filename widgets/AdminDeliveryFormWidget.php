<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с формой редактирования данных товара
 */
class AdminDeliveryFormWidget extends AbstractBaseWidget
{
    /**
     * @var Model
     */
    private $delivery;
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
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['modelForm'] = \Yii::configure($this->form, [
                'id'=>$this->delivery->id,
                'name'=>$this->delivery->name,
                'description'=>$this->delivery->description,
                'price'=>$this->delivery->price,
                'active'=>$this->delivery->active,
            ]);
            
            $renderArray['formId'] = sprintf('admin-delivery-edit-form-%d', $this->delivery->id);
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['cols'] = 20;
            $renderArray['rows'] = 5;
            
            $renderArray['formAction'] = Url::to(['/admin/delivery-change']);
            $renderArray['button'] = \Yii::t('base', 'Save');
            $renderArray['buttonCancel'] = \Yii::t('base', 'Cancel');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminDeliveryFormWidget::delivery
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
     * Присваивает значение AdminDeliveryFormWidget::form
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
     * Присваивает значение AdminDeliveryFormWidget::template
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
