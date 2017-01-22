<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\MailingForm;

/**
 * Формирует HTML строку с данными о текущих подписках
 */
class AccountMailingsUnsubscribeWidget extends AbstractBaseWidget
{
    /**
     * @var array MailingsModel
     */
    private $mailings;
    /**
     * @var array MailingForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    public $view;
    
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Current subscriptions');
            
            if (!empty($this->mailings)) {
                foreach ($this->mailings as $mailing) {
                    $set = [];
                    $set['name'] = $mailing->name;
                    $set['description'] = $mailing->description;
                    
                    $form = clone $this->form;
                    
                    $set['modelForm'] = \Yii::configure($form, ['id'=>$mailing->id]);
                    $set['formId'] = sprintf('mailing-cancellation-form-%d', $mailing->id);
                    
                    $set['ajaxValidation'] = false;
                    $set['validateOnSubmit'] = false;
                    $set['validateOnChange'] = false;
                    $set['validateOnBlur'] = false;
                    $set['validateOnType'] = false;
                    
                    $set['formAction'] = Url::to(['/account/subscriptions-cancel']);
                    $set['button'] = \Yii::t('base', 'Cancel');
                    
                    $renderArray['mailings'][] = $set;
                }
            }
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array MailingsModel свойству AccountMailingsUnsubscribeWidget::mailings
     * @param $mailings array
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
     * Присваивает MailingForm свойству AccountMailingsUnsubscribeWidget::form
     * @param $form MailingForm
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
