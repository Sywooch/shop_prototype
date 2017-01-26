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
    private $tempPassword = null;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->tempPassword)) {
                throw new ErrorException($this->emptyError('tempPassword'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            $renderArray['text'] = \Yii::t('base', 'Your new password:');
            $renderArray['password'] = $this->tempPassword;
            $renderArray['explanation'] = \Yii::t('base', 'For safety, be sure to replace it as soon as possible!');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает пароль свойству PasswordGenerateSuccessWidget::tempPassword
     * @param string $tempPassword
     */
    public function setTempPassword(string $tempPassword)
    {
        try {
            $this->tempPassword = $tempPassword;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству PasswordGenerateSuccessWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству PasswordGenerateSuccessWidget::template
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
