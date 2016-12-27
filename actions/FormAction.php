<?php

namespace app\actions;

use yii\base\ErrorException;
use app\actions\AbstractBaseAction;
use app\services\ServiceInterface;

/**
 * Обрабатывает запрос на обработку формы
 */
class FormAction extends AbstractBaseAction
{
    /**
     * @var object ServiceInterface, обрабатывающий запрос
     */
    private $service;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->service)) {
                throw new ErrorException($this->emptyError('service'));
            }
            
            $result = $this->service->handle(\Yii::$app->request);
            
            if (empty($result)) {
                throw new ErrorException($this->emptyError('result'));
            }
            
            if (is_array($result)) {
                return $this->controller->render($this->view, $result);
            } elseif (is_string($result)) {
                return $this->controller->redirect($result);
            } else {
                throw new ErrorException($this->invalidError('result'));
            }
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
