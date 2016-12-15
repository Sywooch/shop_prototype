<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\web\User;
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
            
            return $this->render($this->view, ['user'=>isset($user) ? $user->email->email : \Yii::t('base', 'Guest'), 'authenticated'=>isset($user) ? true : false]);
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
