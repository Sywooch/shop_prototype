<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\web\User;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с информацией о текущем статусе аутентификации
 */
class UserInfoWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя шаблона
     */
    public $view;
    /**
     * @var object
     */
    private $user;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
            if (empty($this->user)) {
                throw new ErrorException(ExceptionsTrait::emptyError('user'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует HTML строку с информацией о текущем пользователе
     * @return string
     */
    public function run()
    {
        try {
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
     * @param object $user
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
