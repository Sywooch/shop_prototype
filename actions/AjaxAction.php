<?php

namespace app\actions;

use yii\base\ErrorException;
use app\actions\AbstractBaseAction;
use app\handlers\HandlerInterface;

/**
 * Обрабатывает AJAX запрос
 */
class AjaxAction extends AbstractBaseAction
{
    /**
     * @var object HandlerInterface, обрабатывающий запрос
     */
    private $handler;
    
    public function run()
    {
        try {
            if (empty($this->handler)) {
                throw new ErrorException($this->emptyError('handler'));
            }
            
            if (\Yii::$app->request->isAjax !== true) {
                throw new ErrorException($this->invalidError('AJAX'));
            }
            
            return $this->handler->handle(\Yii::$app->request);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwServerError($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает HandlerInterface свойству AjaxAction::handler
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
