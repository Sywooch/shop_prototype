<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку со ссылкой на страницу офрмления заказа
 */
class CartCheckoutLinkWidget extends AbstractBaseWidget
{
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку со ссылкой на страницу офрмления заказа
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['formId'] = 'cart-сheckout-ajax-link';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/cart/сheckout-ajax-form']);
            $renderArray['button'] = \Yii::t('base', 'Checkout');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству CartCheckoutLinkWidget::template
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
