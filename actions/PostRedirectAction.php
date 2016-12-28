<?php

namespace app\actions;

use yii\base\ErrorException;
use app\actions\AbstractBaseAction;
use app\services\ServiceInterface;

/**
 * Обрабатывает запрос на вывод каталога товаров
 */
class PostRedirectAction extends AbstractBaseAction
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
            
            $url = $this->service->handle(\Yii::$app->request->post());
            
            if (empty($url)) {
                throw new ErrorException($this->emptyError('url'));
            }
            
            return $this->controller->redirect($url);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ServiceInterface свойству SaveAction::service
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
