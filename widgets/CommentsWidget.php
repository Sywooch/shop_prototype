<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;

/**
 * Выводит информацию о похожих товарах
 */
class CommentsWidget extends AbstractBaseWidget
{
    /**
     * @var array CommentsModel
     */
    private $comments;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            if (!empty($this->comments)) {
                foreach ($this->comments as $comment) {
                    $set = [];
                    $set['date'] = \Yii::$app->formatter->asDate($comment->date);
                    $set['name'] = $comment->name->name;
                    $set['text'] = $comment->text;
                    $renderArray['comments'][] = $set;
                }
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array CommentsModel свойству CommentsWidget::comments
     * @param array $comments
     */
    public function setComments(array $comments)
    {
        try {
            $this->comments = $comments;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству CommentsModel::template
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
