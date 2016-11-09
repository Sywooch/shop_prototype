<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\actions\AbstractBaseAction;

/**
 * Обрабатывает запрос на вывод 1 записи
 */
class DetailAction extends AbstractBaseAction
{
    public $modelClass;
    public $view;
    public $rememberUrl;
    public $additions;
    public $column;
    public $resultName;
    
    public function run($id)
    {
        try {
            $model = $this->modelClass::findOne([$this->column=>$id]);
            
            if (empty($model)) {
                throw new ErrorException(ExceptionsTrait::emptyError($modelClass));
            }
            
            if (!empty($this->rememberUrl)) {
                Url::remember(Url::current(), $this->rememberUrl);
            }
            
            return $this->controller->render($this->view, ArrayHelper::merge($this->_renderArray, [$this->resultName=>$model]));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
