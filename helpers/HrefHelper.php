<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Коллекция методов для создания ссылок
 */
class HrefHelper
{
    use ExceptionsTrait;
    
    /**
     * Конструирует ссылку в зависимости от значений свойств \Yii::$app->filters
     * @param array $urlArray массив данных для конструирования URL
     * @return array массив данных для конструирования URL
     */
    public static function createHrefFromFilter(array $urlArray)
    {
        try {
            if (empty($urlArray)) {
                throw new ErrorException('Ошибка при передаче данных!');
            }
            if (empty(\Yii::$app->params['categoriesKey'])) {
                throw new ErrorException('Не установлена переменная categoriesKey!');
            }
            if (empty(\Yii::$app->params['subcategoryKey'])) {
                throw new ErrorException('Не установлена переменная subcategoryKey!');
            }
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            
            if (!empty(\Yii::$app->filters->search)) {
                $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>\Yii::$app->filters->search];
            } else {
                if (!empty(\Yii::$app->filters->categories)) {
                    $urlArray = array_merge($urlArray, [\Yii::$app->params['categoriesKey']=>\Yii::$app->filters->categories]);
                }
                if (!empty(\Yii::$app->filters->subcategory)) {
                    $urlArray = array_merge($urlArray, [\Yii::$app->params['subcategoryKey']=>\Yii::$app->filters->subcategory]);
                }
            }
            
            return $urlArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
