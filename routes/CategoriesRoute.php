<?php

namespace app\routes;

use yii\base\{ErrorException,
    Object};
use yii\web\UrlRuleInterface;
use app\exceptions\ExceptionsTrait;
use app\models\{CategoriesModel,
    SubcategoryModel};
use app\validators\StripTagsValidator;

/**
 * Парсит и конструирует URL товарных категорий
 */
class CategoriesRoute extends Object implements UrlRuleInterface
{
    use ExceptionsTrait;
    
    /**
     * @var array массив данных, используется при парсинге URL
     */
    private $parsingArray = [];
    /**
     * @var string строка, представляющая в URL ссылку на главную страницу каталога
     */
    public $all = 'catalog';
    
    /**
     * Парсит запрос и возвращает подходящий маршрут и параметры
     * @return mixed
     */
    public function parseRequest($manager, $request)
    {
        try {
            $pathInfo = $request->getPathInfo();
            $validator = new StripTagsValidator();
            $pathInfo = $validator->validate($pathInfo);
            
            $pathInfo = filter_var($pathInfo, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'#^[a-z-0-9/]+$#u']]);
            if ($pathInfo === false) {
                return false;
            }
            
            list($category, $subcategory) = explode('/', $pathInfo);
            
            $category = $this->parseChunk($category);
            
            if ($category != $this->all) {
                if (CategoriesModel::find()->where(['[[categories.seocode]]'=>$category])->exists()) {
                    $this->parsingArray[\Yii::$app->params['categoryKey']] = $category;
                    if (!empty($subcategory)) {
                        $subcategory = $this->parseChunk($subcategory);
                        if (SubcategoryModel::find()->where(['[[subcategory.seocode]]'=>$subcategory])->exists()) {
                            $this->parsingArray[\Yii::$app->params['subcategoryKey']] = $subcategory;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }
            
            return ['products-list/index', $this->parsingArray];
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
            if ($route !== 'products-list/index') {
                return false;
            }
            
            if (!empty($params[\Yii::$app->params['categoryKey']])) {
                $paramsArray[] = $params[\Yii::$app->params['categoryKey']];
            }
            if (!empty($params[\Yii::$app->params['subcategoryKey']])) {
                $paramsArray[] = $params[\Yii::$app->params['subcategoryKey']];
            }
            
            $result = !empty($paramsArray) ? implode('/', $paramsArray) : $this->all;
            
            if (!empty($params[\Yii::$app->params['pagePointer']])) {
                $result .= '-' . $params[\Yii::$app->params['pagePointer']];
            }
            
            return $result;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Парсит данные из URL
     * @param string $chunk часть URL
     * @return string
     */
    private function parseChunk(string $chunk): string
    {
        try {
            if (preg_match('/^(.+)-(\d{1,3})$/', $chunk, $matches)) {
                $chunk = $matches[1];
                $this->parsingArray[\Yii::$app->params['pagePointer']] = $matches[2];
            }
            
            return $chunk;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
