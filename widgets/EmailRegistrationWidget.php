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
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Hello! This is information about your account!');
            $renderArray['text'] = \Yii::t('base', 'You can operate it in his <a href="{href}">personal account</a>', ['href'=>Url::to(['/user/login'], true)]);
            $renderArray['email'] = \Yii::t('base', 'Your username: {email}', ['email'=>$this->email]);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает email свойству EmailRegistrationWidget::email
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
     * Присваивает имя шаблона свойству EmailRegistrationWidget::template
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
