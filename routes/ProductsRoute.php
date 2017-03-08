<?php

namespace app\routes;

use yii\base\Object;
use yii\web\UrlRuleInterface;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;
use app\validators\StripTagsValidator;

/**
 * Парсит и конструирует URL товарных категорий
 */
class ProductsRoute extends Object implements UrlRuleInterface
{
    use ExceptionsTrait;
    
    /**
     * Парсит запрос и возвращает подходящий маршрут и параметры
     * @return array/bool
     */
    public function parseRequest($manager, $request)
    {
        try {
            $pathInfo = $request->getPathInfo();
            $validator = new StripTagsValidator();
            $pathInfo = $validator->validate($pathInfo);
            
            $pathInfo = filter_var($pathInfo, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'#^[a-z-0-9]+$#u']]);
            if ($pathInfo === false) {
                throw new ErrorException($this->invalidError('pathInfo'));
            }
            
            if (ProductsModel::find()->where(['[[products.seocode]]'=>$pathInfo])->exists()) {
                $paramsArray[\Yii::$app->params['productKey']] = $pathInfo;
            } else {
                return false;
            }
            
            return ['product-detail/index', $paramsArray];
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
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
        }
    }
}
