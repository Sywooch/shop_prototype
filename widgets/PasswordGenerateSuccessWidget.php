<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией об успешной регистрации
 */
class PasswordGenerateSuccessWidget extends AbstractBaseWidget
{
    /**
     * @var string сгенерированный пароль
     */
    public $tempPassword = null;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->tempPassword)) {
                throw new ErrorException($this->emptyError('tempPassword'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Password recovery');
            
            $renderArray['text'] = \Yii::t('base', 'Your new password:');
            $renderArray['password'] = $this->tempPassword;
            $renderArray['explanation'] = \Yii::t('base', 'For safety, be sure to replace it as soon as possible!');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
