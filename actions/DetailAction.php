<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\db\ActiveRecord;
use app\actions\AbstractBaseAction;

/**
 * Обрабатывает запрос на вывод 1 записи
 */
class DetailAction extends AbstractBaseAction
{
    public $modelClass;
    public $column;
    public $view;
    public $resultName;
    public $additions = [];
    
    public function run($id)
    {
        try {
            $model = $this->modelClass::findOne([$this->column=>$id]);
            
            if (empty($model)) {
                throw new ErrorException(ExceptionsTrait::emptyError($this->modelClass));
            }
            
            return $this->controller->render($this->view, ArrayHelper::merge($this->_renderArray, [$this->resultName=>$model]));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
