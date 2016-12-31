<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\UserRegistrationForm;

/**
 * Формирует HTML строку с формой регистрации
 */
class UserRegistrationWidget extends AbstractBaseWidget
{
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с формой регистрации
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Registration');
            
            $renderArray['formModel'] = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
            $renderArray['formId'] = 'registration-form';
            $renderArray['formAction'] = Url::to(['/user/registration']);
            $renderArray['button'] = \Yii::t('base', 'Send');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
