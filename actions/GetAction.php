<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\actions\AbstractBaseAction;
use app\handlers\HandlerInterface;

/**
 * Обрабатывает запрос на обработку формы
 */
class GetAction extends AbstractBaseAction
{
    /**
     * @var object HandlerInterface, обрабатывающий запрос
     */
    private $handler;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->handler)) {
                throw new ErrorException($this->emptyError('handler'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            if (\Yii::$app->request->isGet !== true) {
                throw new ErrorException($this->invalidError('GET'));
            }
            
            $result = $this->handler->handle(\Yii::$app->request);
            
            if (is_array($result) === false || empty($result)) {
                throw new ErrorException($this->invalidError('result'));
            }
            
            return $this->controller->renderPartial($this->view, $result);
        } catch (NotFoundHttpException $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            throw $e;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает HandlerInterface свойству GetAction::handler
     * @param HandlerInterface $handler
     */
    public function setHandler(HandlerInterface $handler)
    {
        try {
            $this->handler = $handler;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
}
