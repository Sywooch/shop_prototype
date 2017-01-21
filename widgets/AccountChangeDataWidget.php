<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\UserUpdateForm;

/**
 * Формирует HTML строку с текущими контактными данными
 */
class AccountChangeDataWidget extends AbstractBaseWidget
{
    /**
     * @var UserUpdateForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Change data');
            
            $renderArray['modelForm'] = $this->form;
            $renderArray['formId'] = 'change-data-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/account/change-data-post']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает UserUpdateForm свойству AccountChangeDataWidget::form
     * @param UserUpdateForm $form
     */
    public function setForm(UserUpdateForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
