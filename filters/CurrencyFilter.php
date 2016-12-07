<?php

namespace app\filters;

use yii\base\{ActionFilter,
    ErrorException};
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\CurrencyModel;
use app\finders\{FinderInterface,
    SessionFinderInterface};

/**
 * Устанавливает валюту для текущего запроса
 */
class CurrencyFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * @var object FinderInterface
     */
    private $sessionFinder;
    /**
     * @var object FinderInterface
     */
    private $finder;
    
    /**
     * Получает данные для валюты из сессии, 
     * если терпит неудачу, загружает валюту по умолчанию 
     * @param object $action объект текущего действия
     * @return bool
     */
    public function beforeAction($action)
    {
        try {
            if (empty($this->sessionFinder)) {
                throw new ErrorException($this->emptyError('sessionFinder'));
            }
            if (empty($this->finder)) {
                throw new ErrorException($this->emptyError('finder'));
            }
            
            $this->sessionFinder->load(['key'=>\Yii::$app->params['currencyKey']]);
            
            if ($this->sessionFinder->find()->isEmpty() === true) {
                $currencyArray = $this->finder->find()->getArray();
                if (empty($currencyArray)) {
                    throw new ErrorException($this->emptyError('currencyArray'));
                }
                SessionHelper::write(\Yii::$app->params['currencyKey'], $currencyArray);
            }
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает SessionFinderInterface свойству CurrencyFilter::sessionFinder
     * @param object $collection SessionFinderInterface
     */
    public function setSessionFinder(SessionFinderInterface $finder)
    {
        try {
            $this->sessionFinder = $finder;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает FinderInterface свойству CurrencyFilter::finder
     * @param object $collection FinderInterface
     */
    public function setFinder(FinderInterface $finder)
    {
        try {
            $this->finder = $finder;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
