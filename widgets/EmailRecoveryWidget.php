<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку содержащую ссылку для смены пароля
 */
class EmailRecoveryWidget extends AbstractBaseWidget
{
    /**
     * @var string уникальный ключ, который будет добавлен к ссылке
     */
    public $key;
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
            if (empty($this->key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Hello! This guide for the recovery of your password!');
            $renderArray['text'] = \Yii::t('base', 'To ensure that we have generated the new password for you, just go to this link');
            $renderArray['href'] = Url::to(['/user/generate', \Yii::$app->params['recoveryKey']=>$this->key], true);
            $renderArray['hrefText'] = \Yii::t('base', 'link to recovery');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
