<?php

namespace app\filters;

use yii\base\{ActionFilter,
    ErrorException};
use yii\web\Response;
use app\exceptions\ExceptionsTrait;

/**
 * Проверяет запрос на наличие GET параметров
 */
class GetEmptyFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * @var string параметр, который будет проверен
     */
    public $parameter;
    /**
     * @var URL для редиректа
     */
    public $redirect;
    
    public function beforeAction($action)
    {
        try {
            if (empty($this->parameter)) {
                throw new ErrorException(ExceptionsTrait::methodError(__CLASS__ . '::' . __METHOD__));
            }
            
            if (empty(\Yii::$app->request->get($this->parameter))) {
                if (!empty($this->redirect)) {
                    return \Yii::$app->getResponse()->redirect($this->redirect)->send();
                } else {
                    throw new ErrorException(ExceptionsTrait::emptyError($this->parameter));
                }
            }
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
