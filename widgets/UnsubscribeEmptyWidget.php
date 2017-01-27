<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с сообщением об отсутствии подписок для переданного email
 */
class UnsubscribeEmptyWidget extends AbstractBaseWidget
{
    /**
     * @var string email
     */
    private $email;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку
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
            
            $renderArray['header'] = \Yii::t('base', 'Unsubscribe');
            $renderArray['text'] = \Yii::t('base', 'Email {placeholder} not associated with any mailings!', ['placeholder'=>$this->email]);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает email свойству UnsubscribeEmptyWidget::email
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
     * Присваивает имя шаблона свойству UnsubscribeEmptyWidget::template
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
