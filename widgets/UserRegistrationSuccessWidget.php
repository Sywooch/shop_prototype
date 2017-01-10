<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Html;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией об успешной регистрации
 */
class UserRegistrationSuccessWidget extends AbstractBaseWidget
{
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Registration');
            
            $renderArray['text'] = \Yii::t('base', 'You have successfully signed up! Now you can log in using your E-mail and password');
            $renderArray['href'] = Html::a(\Yii::t('base', 'Login'), ['/user/login']);
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
