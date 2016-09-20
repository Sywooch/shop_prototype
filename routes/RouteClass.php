<?php

namespace app\routes;

use yii\base\Object;
use yii\web\UrlRuleInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Парсит и конструирует URL
 */
class RouteClass extends Object implements UrlRuleInterface
{
    use ExceptionsTrait;
    
    private $_category;
    private $_subcategory;
    private $_id;
    private $_route = false;
    
    public function parseRequest($manager, $request)
    {
        try {
            $pathInfo = $request->getPathInfo();
            
            $categories = \yii\helpers\ArrayHelper::getColumn(\app\models\CategoriesModel::find()->all(), 'seocode');
            $subcategory = \yii\helpers\ArrayHelper::getColumn(\app\models\SubcategoryModel::find()->all(), 'seocode');
            
            if (preg_match('/^(' . implode('|', $categories) . ')\/?/', $pathInfo)) {
                $pathArray = explode('/', $pathInfo);
                $this->_route = [];
                foreach ($pathArray as $path) {
                    if (in_array($path, $categories)) {
                        $this->_route['category'] = $path;
                        continue;
                    }
                    if (in_array($path, $subcategory)) {
                        $this->_route['subcategory'] = $path;
                        continue;
                    }
                }
                return array_merge(['products-list/index'], [$this->_route]);
            }
            
            if (preg_match('/^catalog/', $pathInfo)) {
                return ['products-list/index', []];
            }
            
            return false;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function createUrl($manager, $route, $params)
    {
        try {
            if ($route !== 'products-list/index') {
                return false;
            }
            
            $result = [];
            if (!empty($params['category'])) {
                $result[] = $params['category'];
            }
            if (!empty($params['subcategory'])) {
                $result[] = $params['subcategory'];
            }
            /*if (!empty($params['page'])) {
                $result[] = $params['page'];
            }*/
            return implode('/', $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
