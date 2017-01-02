<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией об успешной регистрации
 */
class EmailRegistrationWidget extends AbstractBaseWidget
{
    /**
     * @var string email регистрируемого пользователя
     */
    public $email;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с информацией об успешной регистрации
     * @return string
     */
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
            
            $renderArray['header'] = \Yii::t('base', 'Hello! This is information about your account!');
            $renderArray['text'] = \Yii::t('base', 'You can operate it in his <a href="{href}">personal account</a>', ['href'=>Url::to(['/user/login'], true)]);
            $renderArray['email'] = \Yii::t('base', 'Your username: {email}', ['email'=>Html::encode($this->email)]);
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
