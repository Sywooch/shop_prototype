<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;
use app\forms\ChangeCurrencyForm;
use app\services\ServiceInterface;
use app\models\CurrencyModel;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends AbstractBaseWidget
{
    /**
     * @var array CurrencyModel
     */
    private $currency;
    /**
     * @var ServiceInterface, возвращает текущую валюту
     */
    private $service;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->service)) {
                throw new ErrorException($this->emptyError('service'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Currency');
            $renderArray['currency'] = $this->currency;
            
            $currencyModel = $this->service->handle();
            if (!$currencyModel instanceof CurrencyModel) {
                throw new ErrorException($this->invalidError('currencyModel'));
            }
            
            $renderArray['formModel'] = new ChangeCurrencyForm([
                'scenario'=>ChangeCurrencyForm::GET,
                'url'=>Url::current(),
                'id'=>$currencyModel->id
            ]);
            $renderArray['formId'] = 'set-currency-form';
            $renderArray['formAction'] = Url::to(['/currency/set']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array CurrencyWidget::currency
     * @param array $currency
     */
    public function setCurrency(array $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ServiceInterface CurrencyWidget::service
     * @param ServiceInterface $service
     */
    public function setService(ServiceInterface $service)
    {
        try {
            $this->service = $service;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
