<?php

namespace app\actions;

use yii\base\ErrorException;
use app\actions\AbstractBaseAction;
use app\services\ServiceInterface;

/**
 * Обрабатывает запрос на вывод каталога товаров
 */
class RedirectAction extends AbstractBaseAction
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
            if (\Yii::$app->request->isGet === true) {
                throw new ErrorException($this->invalidError('POST'));
            }
            
            $result = $this->service->handle(\Yii::$app->request);
            
            if (\Yii::$app->request->isAjax) {
                return $result;
            }
            
            if (is_string($result) === false) {
                throw new ErrorException($this->invalidError('result'));
            }
            if (empty($result)) {
                throw new ErrorException($this->emptyError('result'));
            }
            
            return $this->controller->redirect($result);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ServiceInterface свойству RedirectAction::service
     * @param ServiceInterface $service 
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
