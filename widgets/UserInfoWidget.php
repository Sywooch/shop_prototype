<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\web\User;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\UserLoginForm;

/**
 * Формирует HTML строку с информацией о текущем статусе аутентификации
 */
class UserInfoWidget extends AbstractBaseWidget
{
    /**
     * @var object User
     */
    private $user;
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
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $isGuest = $this->user->isGuest;
            
            $renderArray = [];
            
            if ($isGuest === true) {
                $placeholder = \Yii::t('base', 'Guest');
                $renderArray['loginHref'] = Url::to(['/user/login']);
                $renderArray['loginText'] = \Yii::t('base', 'Login');
                $renderArray['registrationHref'] = Url::to(['/user/registration']);
                $renderArray['registrationText'] = \Yii::t('base', 'Registration');
            } else {
                $user = $this->user->identity;
                $placeholder = $user->email->email;
                $renderArray['formModel'] = new UserLoginForm(['id'=>$user->id]);
                $renderArray['formId'] = 'user-logout-form';
                $renderArray['formAction'] = Url::to(['/user/logout']);
                $renderArray['button'] = \Yii::t('base', 'Logout');
                
                $renderArray['formIdSettings'] = 'account-settings-form';
                $renderArray['formActionSettings'] = Url::to(['/account/index']);
                $renderArray['buttonSettings'] = \Yii::t('base', 'Account settings');
            }
            
            $renderArray['isGuest'] = $isGuest;
            $renderArray['header'] = \Yii::t('base', 'Hello, {placeholder}!', ['placeholder'=>$placeholder]);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает User свойству UserInfoWidget::user
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
     * Присваивает имя шаблона свойству UserInfoWidget::template
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
