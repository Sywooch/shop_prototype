<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\interfaces\SearchFilterInterface;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object SearchFilterInterface для поиска данных по запросу
     */
    private $filterClass;
    /**
     * @var string сценарий поиска
     */
    public $filterScenario;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            $currencyArray = $this->filterClass->search($this->filterScenario);
            
            if (empty($currencyArray)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$currencyArray'));
            }
            
            return $this->render($this->view, ['currencyArray'=>$currencyArray]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setFilterClass(SearchFilterInterface $filterClass)
    {
        try {
            $this->filterClass = $filterClass;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
