<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
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
     * Конструирует HTML строку с информацией о текущем пользователе
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$this->view'));
            }
            
            if (\Yii::$app->user->isGuest === false) {
                $user = \Yii::$app->user->identity;
            }
            
            return $this->render($this->view, ['user'=>isset($user) ? $user->email->email : \Yii::t('base', 'Guest'), 'authenticated'=>isset($user) ? true : false]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
