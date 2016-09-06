<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Коллекция методов для создания хеша
 */
class HashHelper
{
    use ExceptionsTrait;
    
    /**
     * Конструирует хеш с помощью функции md5
     * @param array $inputArray массив данных для конструирования хеша
     * @return string результирующий хеш
     */
    public static function createHash(Array $inputArray)
    {
        try {
            if (empty($inputArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            
            $inputString = implode('-', $inputArray);
            return md5($inputString);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует хеш с помощью функции md5, 
     * основываясь на данных массива $_GET
     * @param array $inputArray массив данных для конструирования хеша
     * @return string результирующий хеш
     */
    public static function createHashFromGet()
    {
        try {
            if (empty(\Yii::$app->params['categoriesKey'])) {
                throw new ErrorException('Не установлена переменная categoriesKey!');
            }
            if (empty(\Yii::$app->params['subcategoryKey'])) {
                throw new ErrorException('Не установлена переменная subcategoryKey!');
            }
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            
            return self::createHash([
                !empty(\Yii::$app->request->get(\Yii::$app->params['categoriesKey'])) ? \Yii::$app->request->get(\Yii::$app->params['categoriesKey']) : 'none', 
                !empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])) ? \Yii::$app->request->get(\Yii::$app->params['subcategoryKey']) : 'none', 
                !empty(\Yii::$app->request->get(\Yii::$app->params['searchKey'])) ? \Yii::$app->request->get(\Yii::$app->params['searchKey']) : 'none'
            ]);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует хеш с помощью функции md5, 
     * основываясь на данных \Yii::$app->filters
     * @param array $inputArray массив данных для конструирования хеша
     * @return string результирующий хеш
     */
    public static function createHashFromFilters()
    {
        try {
            return self::createHash([
                !empty(\Yii::$app->filters->categories) ? \Yii::$app->filters->categories : 'none', 
                !empty(\Yii::$app->filters->subcategory) ? \Yii::$app->filters->subcategory : 'none', 
                !empty(\Yii::$app->filters->search) ? \Yii::$app->filters->search : 'none'
            ]);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
