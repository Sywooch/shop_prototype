<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\actions\AbstractBaseAction;
use app\services\SearchServiceInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Обрабатывает запрос на вывод 1 записи
 */
class DetailAction extends AbstractBaseAction
{
    /**
     * @var object для поиска данных по запросу
     */
    private $finderClass;
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
            $product = $this->finderClass->search(\Yii::$app->request->get());
            
            if (empty($product)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$product'));
            }
            
            Url::remember(Url::current(), 'shop');
            
            return $this->controller->render($this->view, ArrayHelper::merge($this->_renderArray, ['model'=>$product]));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setFinderClass(SearchServiceInterface $finderClass)
    {
        try {
            $this->finderClass = $finderClass;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
