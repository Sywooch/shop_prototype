<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\web\User;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с информацией о текущем статусе аутентификации
 */
class ModUserInfoWidget extends AbstractBaseWidget
{
    /**
     * @var object User
     */
    private $user;
    /**
     * @var AbstractBaseForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с информацией о текущем пользователе
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->user)) {
                throw new ErrorException($this->emptyError('user'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $isGuest = $this->user->isGuest;
            
            $renderArray = [];
            
            if ($isGuest === true) {
                $renderArray['loginHref'] = Url::to(['/user/login']);
                $renderArray['loginText'] = \Yii::t('base', 'Login');
                $renderArray['registrationHref'] = Url::to(['/user/registration']);
                $renderArray['registrationText'] = \Yii::t('base', 'Registration');
            } else {
                $user = $this->user->identity;
                
                $renderArray['email'] = $user->email->email;
                $renderArray['settingsHref'] = Url::to(['/account/index']);
                $renderArray['logoutText'] = \Yii::t('base', 'Logout');
                $renderArray['id'] = $user->id;
                
                $renderArray['modelForm'] = $this->form;
                $renderArray['formId'] = 'user-logout-form';
                $renderArray['formAction'] = Url::to(['/user/logout']);
                $renderArray['button'] = \Yii::t('base', 'Logout');
                
                $renderArray['formSettings']['ajaxValidation'] = false;
                $renderArray['formSettings']['validateOnSubmit'] = false;
                $renderArray['formSettings']['validateOnChange'] = false;
                $renderArray['formSettings']['validateOnBlur'] = false;
                $renderArray['formSettings']['validateOnType'] = false;
            }
            
            $renderArray['isGuest'] = $isGuest;
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ModUserInfoWidget::user
     * @param User $user
     */
    public function setUser(User $user)
    {
        try {
            $this->user = $user;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ModUserInfoWidget::form
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
     * Присваивает значение ModUserInfoWidget::template
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
