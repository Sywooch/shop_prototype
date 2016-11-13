<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\actions\AbstractBaseAction;
use app\interfaces\SearchFilterInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Обрабатывает запрос на вывод 1 записи
 */
class DetailAction extends AbstractBaseAction
{
    /**
     * @var object FinderSearchInterface для поиска данных по запросу
     */
    private $filterClass;
    /**
     * @var string сценарий поиска
     */
    public $filterScenario;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    /**
     * @var array массив дополнительных данных, которые будут доступны в шаблоне
     */
    public $additions = [];
    
    public function run()
    {
        try {
            $model = $this->filterClass->search($this->filterScenario, \Yii::$app->request->get());
            
            if (empty($model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$model'));
            }
            
            Url::remember(Url::current(), \Yii::$app->id);
            
            return $this->controller->render($this->view, ArrayHelper::merge($this->_renderArray, ['model'=>$model]));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setFilterClass(SearchFilterInterface $filterClass)
    {
        try {
            $this->filterClass = $filterClass;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
