<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией об успешной отправки письма 
 * с инструкциями для восстановления пароля
 */
class UserRecoverySuccessWidget extends AbstractBaseWidget
{
    /**
     * @var string email
     */
    public $email;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Password recovery');
            $renderArray['text'] = \Yii::t('base', 'Instructions for restoring the password sent to');
            $renderArray['email'] = $this->email;
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
