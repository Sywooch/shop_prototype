<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с формой редактирования данных товара
 */
class AdminCommentFormWidget extends AbstractBaseWidget
{
    /**
     * @var Model
     */
    private $comment;
    /**
     * @var array
     */
    private $activeStatuses;
    /**
     * @var AbstractBaseForm
     */
    private $form;
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
            if (empty($this->comment)) {
                throw new ErrorException($this->emptyError('comment'));
            }
            if (empty($this->activeStatuses)) {
                throw new ErrorException($this->emptyError('activeStatuses'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['modelForm'] = \Yii::configure($this->form, [
                'id'=>$this->comment->id,
                'text'=>$this->comment->text,
                'active'=>$this->comment->active,
            ]);
            
            $renderArray['activeStatuses'] = $this->activeStatuses;
            
            $renderArray['formId'] = sprintf('admin-comment-edit-form-%d', $this->comment->id);
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['cols'] = 20;
            $renderArray['rows'] = 5;
            
            $renderArray['formAction'] = Url::to(['/admin/comment-change']);
            $renderArray['button'] = \Yii::t('base', 'Save');
            $renderArray['buttonCancel'] = \Yii::t('base', 'Cancel');
            
            $renderArray['id'] = $this->comment->id;
            $renderArray['date'] = \Yii::$app->formatter->asDate($this->comment->date);
            $renderArray['name'] = !empty($this->comment->id_name) ? $this->comment->name->name: null;
            $renderArray['email'] = !empty($this->comment->id_email) ? $this->comment->email->email: null;
            $renderArray['commentName'] = !empty($this->comment->id_product) ? $this->comment->product->name : null;
            $renderArray['commentHref'] = !empty($this->comment->id_product) ? Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$this->comment->product->seocode]) : null;
            
            $renderArray['idHeader'] = \Yii::t('base', 'Comment Id');
            $renderArray['dateHeader'] = \Yii::t('base', 'Date added');
            $renderArray['nameHeader'] = \Yii::t('base', 'Commentator');
            $renderArray['emailHeader'] = \Yii::t('base', 'Email');
            $renderArray['textHeader'] = \Yii::t('base', 'Comment text');
            $renderArray['activeHeader'] = \Yii::t('base', 'Active');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCommentFormWidget::comment
     * @param Model $comment
     */
    public function setComment(Model $comment)
    {
        try {
            $this->comment = $comment;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCommentFormWidget::activeStatuses
     * @param array $activeStatuses
     */
    public function setActiveStatuses(array $activeStatuses)
    {
        try {
            $this->activeStatuses = $activeStatuses;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCommentFormWidget::form
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
     * Присваивает значение AdminCommentFormWidget::template
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
