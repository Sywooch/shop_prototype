<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует фронтенд футер
 */
class FrontendFooterWidget extends AbstractBaseWidget
{
    /**
     * @var array AbstractBaseForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Формирует меню
     */
    public function run()
    {
        try {
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['links'][] = [
                'text'=>\Yii::t('base', 'Newsletter'),
                'link'=>'/',
            ];
            
            $renderArray['links'][] = [
                'text'=>\Yii::t('base', 'About'),
                'link'=>'/',
            ];
            
            $renderArray['links'][] = [
                'text'=>\Yii::t('base', 'Shipping/Returns'),
                'link'=>'/',
            ];
            
            $renderArray['links'][] = [
                'text'=>\Yii::t('base', 'Contact'),
                'link'=>'/',
            ];
            
            $renderArray['links'][] = [
                'text'=>\Yii::t('base', 'Terms'),
                'link'=>'/',
            ];
            
            $renderArray['formText'] = \Yii::t('base', 'Let\'s stay in touch');
            
            $renderArray['formModel'] = $this->form;
            
            $renderArray['formId'] = 'footer-mailings-form';
            $renderArray['formAction'] = Url::to(['/mailings/save']);
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение FrontendFooterWidget::form
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
     * Присваивает значение FrontendFooterWidget::template
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
