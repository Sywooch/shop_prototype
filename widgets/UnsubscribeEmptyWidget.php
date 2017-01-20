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
    public $email;
    /**
     * @var string имя шаблона
     */
    public $view;
    
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Unsubscribe');
            $renderArray['text'] = \Yii::t('base', 'Email {placeholder} not associated with any mailings!', ['placeholder'=>$this->email]);
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
