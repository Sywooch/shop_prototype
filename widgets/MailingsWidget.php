<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\MailingForm;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class MailingsWidget extends AbstractBaseWidget
{
    /**
     * @var array MailingsModel
     */
    private $mailings;
    /**
     * @var object MailingForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Available mailings');
            $renderArray['headerForm'] = \Yii::t('base', 'Sign up now!');
            
            foreach ($this->mailings as $mailing) {
                $renderArray['mailingsSet'][] = ['name'=>$mailing->name, 'description'=>$mailing->description];
            }
            
            ArrayHelper::multisort($this->mailings, 'name');
            $renderArray['mailings'] = ArrayHelper::map($this->mailings, 'id', 'name');
            
            $renderArray['formModel'] = $this->form;
            $renderArray['formId'] = 'mailings-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['multiple'] = true;
            
            $renderArray['formAction'] = Url::to(['/mailings/save']);
            $renderArray['button'] = \Yii::t('base', 'Subscribe');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array MailingsModel свойству MailingsWidget::mailings
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
     * Присваивает MailingForm свойству MailingsWidget::form
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
}
