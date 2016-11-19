<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\Helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\repository\RepositoryInterface;
use app\models\QueryCriteria;

/**
 * Формирует HTML строку с формой выбора валюты
 */
class CurrencyWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object RepositoryInterface
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
            $currency = $this->repository->getGroup();
            
            $currency = ArrayHelper::map($currency, 'id', 'code');
            asort($currency, SORT_STRING);
            
            return $this->render($this->view, ['currency'=>$currency]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству CurrencyWidget::repository
     * @param object $repository RepositoryInterface
     */
    public function setRepository(RepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
