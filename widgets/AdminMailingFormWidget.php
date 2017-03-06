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
class AdminMailingFormWidget extends AbstractBaseWidget
{
    /**
     * @var Model
     */
    private $mailing;
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
            if (empty($this->mailing)) {
                throw new ErrorException($this->emptyError('mailing'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['modelForm'] = \Yii::configure($this->form, [
                'id'=>$this->mailing->id,
                'name'=>$this->mailing->name,
                'description'=>$this->mailing->description,
                'active'=>$this->mailing->active,
            ]);
            
            $renderArray['formId'] = sprintf('admin-mailing-edit-form-%d', $this->mailing->id);
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['cols'] = 20;
            $renderArray['rows'] = 5;
            
            $renderArray['formAction'] = Url::to(['/admin/mailing-change']);
            $renderArray['button'] = \Yii::t('base', 'Save');
            $renderArray['buttonCancel'] = \Yii::t('base', 'Cancel');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminMailingFormWidget::mailing
     * @param Model $mailing
     */
    public function setMailing(Model $mailing)
    {
        try {
            $this->mailing = $mailing;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminMailingFormWidget::form
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
     * Присваивает значение AdminMailingFormWidget::template
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
