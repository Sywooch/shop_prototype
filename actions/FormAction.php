<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $result = $this->service->handle(\Yii::$app->request);
            
            if (\Yii::$app->request->isAjax) {
                return $result;
            }
            
            if (empty($result)) {
                throw new ErrorException($this->emptyError('result'));
            }
            
            switch (gettype($result)) {
                case 'array':
                    return $this->controller->render($this->view, $result);
                case 'string':
                       return $this->controller->redirect($result);
                default:
                    throw new ErrorException($this->invalidError('result'));
            }
        } catch (NotFoundHttpException $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            throw $e;
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
