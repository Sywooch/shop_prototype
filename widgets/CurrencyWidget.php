<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\Helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\repository\DbRepositoryInterface;
use app\models\QueryCriteria;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object DbRepositoryInterface для поиска данных по запросу
     */
    private $repository;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $criteria = new QueryCriteria();
            $criteria->select(['id', 'code']);
            $this->repository->setCriteria($criteria);
            $currencyArray = $this->repository->getGroup();
            
            if (empty($currencyArray)) {
                throw new ErrorException(ExceptionsTrait::emptyError('currencyArray'));
            }
            
            $currencyArray = ArrayHelper::map($currencyArray, 'id', 'code');
            asort($currencyArray, SORT_STRING);
            
            return $this->render($this->view, ['currencyArray'=>$currencyArray]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает DbRepositoryInterface свойству CurrencyWidget::repository
     * @param object $repository DbRepositoryInterface
     */
    public function setRepository(DbRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
