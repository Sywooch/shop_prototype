<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с формой регистрации
 */
class PasswordGenerateEmptyWidget extends AbstractBaseWidget
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
            
            $renderArray['header'] = \Yii::t('base', 'Password recovery');
            $renderArray['text'] = \Yii::t('base', 'Unfortunately, the link you\'ve followed is invalid. To solve this problem you can contact the administrator');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
