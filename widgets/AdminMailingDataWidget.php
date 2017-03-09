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
class AdminMailingDataWidget extends AbstractBaseWidget
{
    /**
     * @var array Model
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
            
            $renderArray['id'] = $this->mailing->id;
            $renderArray['name'] = $this->mailing->name;
            $renderArray['description'] = $this->mailing->description;
            $renderArray['active'] = $this->mailing->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['formIdChange'] = sprintf('admin-mailing-get-form-%d', $this->mailing->id);
            $renderArray['formActionChange'] = Url::to(['/admin/mailing-form']);
            $renderArray['buttonChange'] = \Yii::t('base', 'Change');
            
            $renderArray['formIdDelete'] = sprintf('admin-mailing-delete-form-%d', $this->mailing->id);
            $renderArray['formActionDelete'] = Url::to(['/admin/mailing-delete']);
            $renderArray['buttonDelete'] = \Yii::t('base', 'Delete');
            
            $renderArray['formSettings']['ajaxValidation'] = false;
            $renderArray['formSettings']['validateOnSubmit'] = false;
            $renderArray['formSettings']['validateOnChange'] = false;
            $renderArray['formSettings']['validateOnBlur'] = false;
            $renderArray['formSettings']['validateOnType'] = false;
            
            $renderArray['nameHeader'] = \Yii::t('base', 'Name');
            $renderArray['descriptionHeader'] = \Yii::t('base', 'Description');
            $renderArray['activeHeader'] = \Yii::t('base', 'Active');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminMailingDataWidget::mailing
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
     * Присваивает значение AdminMailingDataWidget::form
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
     * Присваивает значение AdminMailingDataWidget::template
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
