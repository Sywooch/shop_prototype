<?php

namespace app\routes;

use yii\base\Object;
use yii\web\UrlRuleInterface;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\{CategoriesModel,
    SubcategoryModel};

/**
 * Парсит и конструирует URL товарных категорий
 */
class CategoriesRoute extends Object implements UrlRuleInterface
{
    use ExceptionsTrait;
    
    /**
     * @var array массив данных
     */
    private $_route = [];
    
    /**
     * Парсит запрос и возвращает подходящий маршрут и параметры
     * @return array/bool
     */
    public function parseRequest($manager, $request)
    {
        try {
            $pathInfo = $request->getPathInfo();
            
            $categoriesArray = ArrayHelper::getColumn(CategoriesModel::find()->all(), 'seocode');
            
            if (preg_match('/^(' . implode('|', $categoriesArray) . ')/', $pathInfo)) {
                list($category, $subcategory) = explode('/', $pathInfo);
                
                if (!empty($category)) {
                    $this->run($category, 'category', $categoriesArray);
                }
                
                if (!empty($subcategory)) {
                    $subcategoryArray = ArrayHelper::getColumn(SubcategoryModel::find()->all(), 'seocode');
                    $this->run($subcategory, 'subcategory', $subcategoryArray);
                }
                
                if (!empty($this->_route)) {
                    return array_merge(['products-list/inside'], [$this->_route]);
                }
            
            }
            return false;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        } finally {
            $this->_route = [];
        }
    }
    
    /**
     * Конструирует URL в соответствии с роутом и параметрами
     * @return string/bool
     */
    public function createUrl($manager, $route, $params)
    {
        try {
            if ($route !== 'products-list/inside') {
                return false;
            }
            
            if (!empty($params['category'])) {
                $this->_route[] = $params['category'];
            }
            if (!empty($params['subcategory'])) {
                $this->_route[] = $params['subcategory'];
            }
            
            $result = implode('/', $this->_route);
            
            if (!empty($params['page'])) {
                $result .= '-' . $params['page'];
            }
            
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        } finally {
            $this->_route = [];
        }
    }
    
    /**
     * Парсит данные из URL
     * @return bool
     */
    private function run(string $name, string $key, array $array)
    {
        try {
            if (preg_match('/^(.+)-(\d{1,3})$/', $name, $matches)) {
                $name = $matches[1];
                $page = $matches[2];
            }
            if (in_array($name, $array)) {
                $this->_route[$key] = $name;
                if (!empty($page)) {
                    $this->_route['page'] = $page;
                }
                return true;
            }
            $this->_route = [];
            return false;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
