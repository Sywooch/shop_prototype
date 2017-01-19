<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\MailingForm;

/**
 * Выводит форму добавления нововго комментария
 */
class UnsubscribeFormWidget extends AbstractBaseWidget
{
    /**
     * @var object MailingForm
     */
    private $form;
    /**
     * @var array MailingsModel
     */
    private $mailings;
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
            if (empty($this->mailings)) {
                throw new ErrorException($this->emptyError('mailings'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
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
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает MailingForm свойству UnsubscribeFormWidget::form
     * @param MailingForm $form
     */
    public function setForm(MailingForm $form)
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
}
