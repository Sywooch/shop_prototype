<?php

namespace app\actions;

use yii\base\ErrorException;
use app\actions\AbstractBaseAction;
use app\services\ServiceInterface;

/**
 * Обрабатывает запрос на вывод каталога товаров
 */
class AjaxAction extends AbstractBaseAction
{
    /**
     * @var object ServiceInterface, обрабатывающий запрос
     */
    private $service;
    
    public function run()
    {
        try {
            if (empty($this->service)) {
                throw new ErrorException($this->emptyError('service'));
            }
            if (\Yii::$app->request->isAjax !== true) {
                throw new ErrorException($this->invalidError('AJAX'));
            }
            
            return $this->service->handle(\Yii::$app->request);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ServiceInterface свойству AjaxAction::service
     * @param $service ServiceInterface
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
