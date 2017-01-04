<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\CommentForm;

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
     * @var object CommentForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Comments');
            
            $renderArray['modelForm'] = $this->form;
            $renderArray['formId'] = 'add-comment-form';
            $renderArray['formAction'] = Url::to(['']);
            $renderArray['button'] = \Yii::t('base', 'Send');
            
            if (!empty($this->comments)) {
                foreach ($this->comments as $comment) {
                    $set = [];
                    $set['date'] = \Yii::$app->formatter->asDate($comment->date);
                    $set['name'] = $comment->name;
                    $set['text'] = $comment->text;
                    $renderArray['comments'][] = $set;
                }
            }
            
            return $this->render($this->view, $renderArray);
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
     * Присваивает CommentForm свойству CommentsWidget::form
     * @param CommentForm $form
     */
    public function setForm(CommentForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
