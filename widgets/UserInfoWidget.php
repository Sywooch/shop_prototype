<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\web\User;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;

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
    public $view;
    
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            if ($this->user->isGuest === false) {
                $user = $this->user->identity;
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Hello, {placeholder}!', ['placeholder'=>isset($user) ? $user->email->email : \Yii::t('base', 'Guest')]);
            $renderArray['authenticated'] = isset($user) ? true : false;
            
            $renderArray['loginHref'] = Url::to(['/user/login']);
            $renderArray['loginText'] = \Yii::t('base', 'Login');
            $renderArray['registrationHref'] = Url::to(['/user/registration']);
            $renderArray['registrationText'] = \Yii::t('base', 'Registration');
            
            $renderArray['formId'] = 'user-logout-form';
            $renderArray['formAction'] = Url::to(['/user/logout']);
            $renderArray['userId'] = isset($user) ? $user->id : '';
            $renderArray['button'] = \Yii::t('base', 'Logout');
            
            return $this->render($this->view, $renderArray);
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
}
