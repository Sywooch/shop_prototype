<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\actions\AbstractBaseAction;
use app\services\SearchServiceInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Обрабатывает запрос на вывод каталога товаров
 */
class SearchCollectionAction extends AbstractBaseAction
{
    /**
     * @var object SearchServiceInterface для поиска данных по запросу
     */
    private $service;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->service)) {
                throw new ErrorException(ExceptionsTrait::emptyError('service'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $renderArray = $this->service->handle(\Yii::$app->request->get());
            
            if (empty($renderArray)) {
                throw new ErrorException(ExceptionsTrait::emptyError('renderArray'));
            }
            
            Url::remember(Url::current(), \Yii::$app->id);
            
            return $this->controller->render($this->view, $renderArray);
        } catch (NotFoundHttpException $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            throw $e;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает SearchServiceInterface свойству SearchCollectionAction::service
     * @param object $service SearchServiceInterface
     */
    public function setService(SearchServiceInterface $service)
    {
        try {
            $this->service = $service;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
}
