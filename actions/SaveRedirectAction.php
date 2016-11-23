<?php

namespace app\actions;

use yii\base\ErrorException;
use app\actions\AbstractBaseAction;
use app\exceptions\ExceptionsTrait;
use app\services\SaveServiceInterface;

/**
 * Обрабатывает запрос на добавление товара в корзину
 */
class SaveRedirectAction extends AbstractBaseAction
{
    /**
     * @var object SaveServiceInterface для поиска данных по запросу
     */
    private $service;
    /**
     * @var string URL для редиректа
     */
    public $url;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->service)) {
                throw new ErrorException(ExceptionsTrait::emptyError('service'));
            }
            if (empty($this->url)) {
                throw new ErrorException(ExceptionsTrait::emptyError('url'));
            }
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $this->service->save(\Yii::$app->request->post());
            }
            
            return $this->controller->redirect($this->url);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает SaveServiceInterface свойству SaveRedirectAction::service
     * @param object $service SaveServiceInterface
     */
    public function setService(SaveServiceInterface $service)
    {
        try {
            $this->service = $service;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
