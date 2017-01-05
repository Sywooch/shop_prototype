<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Html;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией об успешном сохранении комментария
 */
class CommentSaveInfoWidget extends AbstractBaseWidget
{
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['text'] = \Yii::t('base', 'Comment saved and will be available after being moderated. Thanks!');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
