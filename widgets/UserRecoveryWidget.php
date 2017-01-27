<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\RecoveryPasswordForm;

/**
 * Формирует HTML строку с формой регистрации
 */
class UserRecoveryWidget extends AbstractBaseWidget
{
    /**
     * @var object RecoveryPasswordForm
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
     * Конструирует HTML строку с формой регистрации
     * @return string
     */
    public function run()
    {
        try {
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
            $renderArray['text'] = \Yii::t('base', 'To continue with password recovery, enter your email');
            
            $renderArray['formModel'] = $this->form;
            $renderArray['formId'] = 'recovery-password-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = true;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/user/recovery-post']);
            $renderArray['button'] = \Yii::t('base', 'Send');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RecoveryPasswordForm свойству UserRecoveryWidget::form
     * @param RecoveryPasswordForm $form
     */
    public function setForm(RecoveryPasswordForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству UserRecoveryWidget::header
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
     * Присваивает имя шаблона свойству UserRecoveryWidget::template
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
