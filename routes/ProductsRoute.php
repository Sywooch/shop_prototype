<?php

namespace app\routes;

use yii\base\Object;
use yii\web\UrlRuleInterface;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

/**
 * Парсит и конструирует URL товарных категорий
 */
class ProductsRoute extends Object implements UrlRuleInterface
{
    use ExceptionsTrait;
    
    /**
     * @var array массив данных, 
     * используется при парсинге и построении URL
     */
    private $_params = [];
    
    /**
     * Парсит запрос и возвращает подходящий маршрут и параметры
     * @return array/bool
     */
    public function parseRequest($manager, $request)
    {
        try {
            $pathInfo = $request->getPathInfo();
            
            if (ProductsModel::find()->where(['[[products.seocode]]'=>$pathInfo])->exists()) {
                $this->_params[\Yii::$app->params['productKey']] = $pathInfo;
            } else {
                return false;
            }
            
            return ['product-detail/index', $this->_params];
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        } finally {
            $this->_params = [];
        }
    }
    
    /**
     * Конструирует URL в соответствии с роутом и параметрами
     * @return string/bool
     */
    public function createUrl($manager, $route, $params)
    {
        try {
            if ($route !== 'product-detail/index') {
                return false;
            }
            
            return !empty($params[\Yii::$app->params['productKey']]) ? $params[\Yii::$app->params['productKey']] : '';
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        } finally {
            $this->_params = [];
        }
    }
}
