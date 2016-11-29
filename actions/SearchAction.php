<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\actions\AbstractBaseAction;
use app\services\ServiceInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Обрабатывает запрос на вывод каталога товаров
 */
class SearchAction extends AbstractBaseAction
{
    /**
     * @var object ServiceInterface для поиска данных по запросу
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
            $dataArray = $this->service->handle(\Yii::$app->request->get());
            
            if (empty($dataArray)) {
                throw new ErrorException(ExceptionsTrait::emptyError('dataArray'));
            }
            
            return $this->controller->render($this->view, $dataArray);
        } catch (NotFoundHttpException $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            throw $e;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ServiceInterface свойству SearchAction::service
     * @param object $service ServiceInterface
     */
    public function setService(ServiceInterface $service)
    {
        try {
            $this->service = $service;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
}
