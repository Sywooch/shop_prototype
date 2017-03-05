<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminMailingsWidget extends AbstractBaseWidget
{
    /**
     * @var array MailingsModel
     */
    private $mailings;
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
            if (empty($this->mailings)) {
                throw new ErrorException($this->emptyError('mailings'));
            }
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
            
            $mailingsArray = [];
            foreach ($this->mailings as $mailing) {
                $set = [];
                $set['name'] = $mailing->name;
                $set['description'] = $mailing->description;
                $set['active'] = $mailing->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
                
                $mailingsForm = clone $this->form;
                $set['modelForm'] = \Yii::configure($mailingsForm, ['id'=>$mailing->id]);
                
                $set['formId'] = sprintf('admin-mailing-get-form-%d', $mailing->id);
                $set['formAction'] = Url::to(['/admin/mailing-form']);
                $set['button'] = \Yii::t('base', 'Change');
                
                $set['formIdDelete'] = sprintf('admin-mailing-delete-form-%d', $mailing->id);
                $set['formActionDelete'] = Url::to(['/admin/mailing-delete']);
                $set['buttonDelete'] = \Yii::t('base', 'Delete');
                
                $set['ajaxValidation'] = false;
                $set['validateOnSubmit'] = false;
                $set['validateOnChange'] = false;
                $set['validateOnBlur'] = false;
                $set['validateOnType'] = false;
                
                $mailingsArray[] = $set;
            }
            
            ArrayHelper::multisort($mailingsArray, 'name', SORT_ASC);
            $renderArray['mailings'] = $mailingsArray;
            
            $renderArray['nameHeader'] = \Yii::t('base', 'Name');
            $renderArray['descriptionHeader'] = \Yii::t('base', 'Description');
            $renderArray['activeHeader'] = \Yii::t('base', 'Active');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminMailingsWidget::mailings
     * @param array $mailings
     */
    public function setMailings(array $mailings)
    {
        try {
            $this->mailings = $mailings;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminMailingsWidget::form
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
     * Присваивает значение AdminMailingsWidget::header
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
     * Присваивает значение AdminMailingsWidget::template
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
