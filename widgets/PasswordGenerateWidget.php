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
class PasswordGenerateWidget extends AbstractBaseWidget
{
    /**
     * @var object RecoveryPasswordForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    public $view;
    
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Password recovery');
            $renderArray['text'] = \Yii::t('base', 'To continue with password recovery, enter your email');
            
            $renderArray['formModel'] = $this->form;
            $renderArray['formId'] = 'generate-password-form';
            $renderArray['ajaxValidation'] = true;
            $renderArray['formAction'] = Url::to(['/user/generate']);
            $renderArray['button'] = \Yii::t('base', 'Send');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RecoveryPasswordForm свойству PasswordGenerateWidget::form
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
}
