<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\CommentForm;

/**
 * Выводит форму добавления нововго комментария
 */
class CommentFormWidget extends AbstractBaseWidget
{
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
            
            $renderArray['modelForm'] = $this->form;
            $renderArray['formId'] = 'comment-form';
            $renderArray['ajaxValidation'] = false;
            $renderArray['formAction'] = Url::to(['/comments/save']);
            $renderArray['button'] = \Yii::t('base', 'Send');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CommentForm свойству CommentFormWidget::form
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
