<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\actions\AbstractBaseAction;
use app\services\ServiceInterface;

/**
 * Обрабатывает запрос на обработку формы
 */
class GetAction extends AbstractBaseAction
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
            if (\Yii::$app->request->isGet !== true) {
                throw new ErrorException($this->invalidError('GET'));
            }
            
            $result = $this->service->handle(\Yii::$app->request);
            
            if (is_array($result) === false) {
                throw new ErrorException($this->invalidError('result'));
            }
            if (empty($result)) {
                throw new ErrorException($this->emptyError('result'));
            }
            
            return $this->controller->render($this->view, $result);
        } catch (NotFoundHttpException $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            throw $e;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ServiceInterface свойству GetAction::service
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
