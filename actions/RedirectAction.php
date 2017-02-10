<?php

namespace app\actions;

use yii\base\ErrorException;
use app\actions\AbstractBaseAction;
use app\handlers\HandlerInterface;

/**
 * Обрабатывает запрос на вывод каталога товаров
 */
class RedirectAction extends AbstractBaseAction
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
            if (\Yii::$app->request->isGet === true) {
                throw new ErrorException($this->invalidError('POST'));
            }
            
            $result = $this->handler->handle(\Yii::$app->request);
            
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
