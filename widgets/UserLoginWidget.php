<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\UserLoginForm;

/**
 * Формирует HTML строку с формой аутентификации
 */
class UserLoginWidget extends AbstractBaseWidget
{
    /**
     * @var object UserLoginForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с формой аутентификации
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Login');
            $renderArray['formModel'] = $this->form;
            $renderArray['formId'] = 'login-form';
            $renderArray['formAction'] = Url::to(['/user/login-post']);
            $renderArray['button'] = \Yii::t('base', 'Send');
            $renderArray['placeholderEmail'] = \Yii::t('base', 'Email');
            $renderArray['placeholderPassw'] = \Yii::t('base', 'Password');
            
            $renderArray['ajaxValidation'] = true;
            $renderArray['validateOnSubmit'] = true;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['recoveryText'] = \Yii::t('base', 'Forgot password?');
            $renderArray['recoveryHref'] = Url::to(['/user/recovery']);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает UserLoginForm свойству UserLoginWidget::form
     * @param UserLoginForm $form
     */
    public function setForm(UserLoginForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству UserLoginWidget::template
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
