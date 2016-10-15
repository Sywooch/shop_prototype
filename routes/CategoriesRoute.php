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
     * @var array массив данных, 
     * используется при парсинге и построении URL
     */
    private $_params = [];
    /**
     * @var string строка, представляющая в URL ссылку на список всех товаров,
     * без разбиения на категории
     */
    public $all = 'catalog';
    
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
                    if (!$this->parseChunk($category, \Yii::$app->params['categoryKey'], $categoriesArray)) {
                        return false;
                    }
                }
                
                if (!empty($subcategory)) {
                    $subcategoryArray = ArrayHelper::getColumn(SubcategoryModel::find()->all(), 'seocode');
                    if (!$this->parseChunk($subcategory, \Yii::$app->params['subcategoryKey'], $subcategoryArray)) {
                        return false;
                    }
                }
            } elseif (preg_match('/^' . $this->all . '/', $pathInfo)) {
                if (!$this->parseChunk($pathInfo, '', [$this->all])) {
                    return false;
                }
            } else {
                return false;
            }
            return ['products-list/index', $this->_params];
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
            if ($route !== 'products-list/index') {
                return false;
            }
            
            if (!empty($params[\Yii::$app->params['categoryKey']])) {
                $this->_params[] = $params[\Yii::$app->params['categoryKey']];
            }
            if (!empty($params[\Yii::$app->params['subcategoryKey']])) {
                $this->_params[] = $params[\Yii::$app->params['subcategoryKey']];
            }
            
            $result = !empty($this->_params) ? implode('/', $this->_params) : $this->all;
            
            if (!empty($params[\Yii::$app->params['pagePointer']])) {
                $result .= '-' . $params[\Yii::$app->params['pagePointer']];
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        } finally {
            $this->_params = [];
        }
    }
    
    /**
     * Парсит данные из URL
     * @param string $chunk часть URL
     * @param string $key ключ, по которому будут сохранены данные, представляющие параметр $_GET
     * @param array $array массив возможных значений параметра $key
     * @return bool
     */
    private function parseChunk(string $chunk, string $key='', array $array=[])
    {
        try {
            if (preg_match('/^(.+)-(\d{1,3})$/', $chunk, $matches)) {
                $chunk = $matches[1];
                $this->_params[\Yii::$app->params['pagePointer']] = $matches[2];
            }
            if (!empty($array)) {
                if (!in_array($chunk, $array)) {
                    $this->_params = [];
                    return false;
                }
                $this->_params[$key] = $chunk;
            }
            
            return true;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
