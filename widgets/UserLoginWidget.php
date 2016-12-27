<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\UserLoginForm;

/**
 * Формирует HTML строку с информацией о текущем статусе аутентификации
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
    public $view;
    
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Login');
            $renderArray['formModel'] = $this->form;
            $renderArray['formId'] = 'login-form';
            $renderArray['formAction'] = Url::to(['/user/login']);
            $renderArray['button'] = \Yii::t('base', 'Send');
            
            return $this->render($this->view, $renderArray);
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
}
