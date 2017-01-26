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
    private $key;
    /**
     * @var string email, который будет добавлен к ссылке
     */
    private $email;
    /**
     * @var string имя шаблона
     */
    private $template;
    
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
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Hello! This guide for the recovery of your password!');
            $renderArray['text'] = \Yii::t('base', 'To ensure that we have generated the new password for you, just go to this link');
            $renderArray['href'] = Url::to(['/user/generate', \Yii::$app->params['recoveryKey']=>$this->key, \Yii::$app->params['emailKey']=>$this->email], true);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ключ свойству EmailRecoveryWidget::key
     * @param string $key
     */
    public function setKey(string $key)
    {
        try {
            $this->key = $key;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает email свойству EmailRecoveryWidget::email
     * @param string $email
     */
    public function setEmail(string $email)
    {
        try {
            $this->email = $email;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству EmailRecoveryWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
