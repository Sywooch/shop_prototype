<?php

namespace app\filters;

use yii\base\ActionFilter;
use yii\helpers\Url;
use app\exceptions\ExceptionsTrait;
use app\helpers\{HashHelper,
    SessionHelper};

/**
 * Применяет фильтры к выборке ProductsModel
 */
class ProductsFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Восстанавливает ранее сохраненное состояние свойств FiltersModel 
     * для текущего URL
     * @param object $action объект текущего действия
     * @return bool
     */
    public function beforeAction($action)
    {
        try {
            $key = Url::current();
            if (preg_match('/(.*)-\d+$/', $key, $matches) === 1) {
                $key = $matches[1];
            }
            $data = SessionHelper::read(HashHelper::createHash([$key]));
            if (!empty($data)) {
                \Yii::configure(\Yii::$app->filters, $data);
            }
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
