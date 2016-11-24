<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\actions\AbstractBaseAction;
use app\services\SearchServiceInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Обрабатывает запрос на вывод списка записей
 */
class ListAction extends AbstractBaseAction
{
    /**
     * @var object SearchServiceInterface для поиска данных по запросу
     */
    private $service;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    /**
     * @var array массив дополнительных данных, которые будут доступны в шаблоне
     */
    public $additions = [];
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->service)) {
                throw new ErrorException(ExceptionsTrait::emptyError('service'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('service'));
            }
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $model = $this->service->search(\Yii::$app->request->get());
            
            if (empty($model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
            }
            
            Url::remember(Url::current(), \Yii::$app->id);
            
            return $this->controller->render($this->view, ArrayHelper::merge($this->_renderArray, ['model'=>$model]));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает SearchServiceInterface свойству DetailAction::service
     * @param object $service SearchServiceInterface
     */
    public function setService(SearchServiceInterface $service)
    {
        try {
            $this->service = $service;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
