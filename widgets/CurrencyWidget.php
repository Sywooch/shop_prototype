<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\interfaces\FinderSearchInterface;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object FinderSearchInterface для поиска данных по запросу
     */
    private $finderClass;
    /**
     * @var string сценарий поиска
     */
    public $finderScenario;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            $currencyArray = $this->finderClass->search($this->finderScenario);
            
            if (empty($currencyArray)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$currencyArray'));
            }
            
            return $this->render($this->view, ['currencyArray'=>$currencyArray]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setFinderClass(FinderSearchInterface $finderClass)
    {
        try {
            $this->finderClass = $finderClass;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
