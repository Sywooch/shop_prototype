<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminCommentDataWidget extends AbstractBaseWidget
{
    /**
     * @var Model
     */
    private $commentsModel;
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
            if (empty($this->commentsModel)) {
                throw new ErrorException($this->emptyError('commentsModel'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['id'] = $this->commentsModel->id;
            $renderArray['date'] = \Yii::$app->formatter->asDate($this->commentsModel->date);
            $renderArray['name'] = !empty($this->commentsModel->id_name) ? $this->commentsModel->name->name: null;
            $renderArray['email'] = !empty($this->commentsModel->id_email) ? $this->commentsModel->email->email: null;
            $renderArray['commentName'] = !empty($this->commentsModel->id_product) ? $this->commentsModel->product->name : null;
            $renderArray['commentHref'] = !empty($this->commentsModel->id_product) ? Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$this->commentsModel->product->seocode]) : null;
            $renderArray['text'] = $this->commentsModel->text;
            $renderArray['active'] = $this->commentsModel->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
            
            $renderArray['modelForm'] = $this->form;
            
            $renderArray['formId'] = sprintf('admin-comment-detail-get-form-%d', $this->commentsModel->id);
            $renderArray['formAction'] = Url::to(['/admin/comment-form']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            
            $renderArray['formIdDelete'] = sprintf('admin-comment-detail-delete-form-%d', $this->commentsModel->id);
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
            
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCommentDataWidget::commentsModel
     * @param Model $commentsModels
     */
    public function setCommentsModel(Model $commentsModel)
    {
        try {
            $this->commentsModel = $commentsModel;
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
     * Присваивает значение AdminCommentDataWidget::template
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
