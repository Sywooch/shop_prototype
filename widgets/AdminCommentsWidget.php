<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminCommentsWidget extends AbstractBaseWidget
{
    /**
     * @var array CommentsModel
     */
    private $comments;
    /**
     * @var AbstractBaseForm
     */
    private $form;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            if (!empty($this->comments)) {
                foreach ($this->comments as $comment) {
                    $set = [];
                    $set['id'] = $comment->id;
                    $set['date'] = \Yii::$app->formatter->asDate($comment->date);
                    $set['name'] = !empty($comment->id_name) ? $comment->name->name: null;
                    $set['email'] = !empty($comment->id_email) ? $comment->email->email: null;
                    $set['commentName'] = !empty($comment->id_product) ? $comment->product->name : null;
                    $set['commentHref'] = !empty($comment->id_product) ? Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$comment->product->seocode]) : null;
                    $set['text'] = $comment->text;
                    $set['active'] = $comment->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
                    
                    $set['formId'] = sprintf('admin-comment-detail-get-form-%d', $comment->id);
                    $set['formIdDelete'] = sprintf('admin-comment-detail-delete-form-%d', $comment->id);
                    
                    $renderArray['comments'][] = $set;
                }
                
                $renderArray['modelForm'] = $this->form;
                
                $renderArray['formAction'] = Url::to(['/admin/comment-form']);
                $renderArray['button'] = \Yii::t('base', 'Change');
                
                $renderArray['formActionDelete'] = Url::to(['/admin/comment-delete']);
                $renderArray['buttonDelete'] = \Yii::t('base', 'Delete');
                
                $renderArray['formSettings']['ajaxValidation'] = false;
                $renderArray['formSettings']['validateOnSubmit'] = false;
                $renderArray['formSettings']['validateOnChange'] = false;
                $renderArray['formSettings']['validateOnBlur'] = false;
                $renderArray['formSettings']['validateOnType'] = false;
                
                $renderArray['idHeader'] = \Yii::t('base', 'Comment Id');
                $renderArray['dateHeader'] = \Yii::t('base', 'Date added');
                $renderArray['nameHeader'] = \Yii::t('base', 'Commentator');
                $renderArray['emailHeader'] = \Yii::t('base', 'Email');
                $renderArray['textHeader'] = \Yii::t('base', 'Comment text');
                $renderArray['activeHeader'] = \Yii::t('base', 'Active');
                
            } else {
                $renderArray['commentsEmpty'] = \Yii::t('base', 'No comments');
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCommentsWidget::comments
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
     * Присваивает значение AdminProductsWidget::form
     * @param AbstractBaseForm $form
     */
    public function setForm(AbstractBaseForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCommentsWidget::header
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
     * Присваивает значение AdminCommentsWidget::template
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
