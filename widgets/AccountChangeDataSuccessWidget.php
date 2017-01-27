<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией об успешном сохранении комментария
 */
class AccountChangeDataSuccessWidget extends AbstractBaseWidget
{
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
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['text'] = \Yii::t('base', 'The data updated successfully!');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AccountChangeDataSuccessWidget::template
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
