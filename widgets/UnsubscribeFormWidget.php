<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Выводит форму добавления нововго комментария
 */
class UnsubscribeFormWidget extends AbstractBaseWidget
{
    /**
     * @var object AbstractBaseForm
     */
    private $form;
    /**
     * @var array MailingsModel
     */
    private $mailings;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->mailings)) {
                throw new ErrorException($this->emptyError('mailings'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Unsubscribe');
            $renderArray['text'] = \Yii::t('base', 'Select the subscription you want to cancel');
            
            $renderArray['modelForm'] = $this->form;
            $renderArray['formId'] = 'unsubscribe-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/mailings/unsubscribe-post']);
            $renderArray['button'] = \Yii::t('base', 'Unsubscribe');
            
            ArrayHelper::multisort($this->mailings, 'name');
            $renderArray['mailings'] = ArrayHelper::map($this->mailings, 'id', 'name');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение UnsubscribeFormWidget::form
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
     * Присваивает array MailingsModel свойству UnsubscribeFormWidget::mailings
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
     * Присваивает имя шаблона свойству UnsubscribeFormWidget::template
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
