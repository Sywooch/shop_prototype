<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;
use app\traits\ExceptionsTrait;

/**
 * Предоставляет методы для загрузки объектов SubcategoryModel
 */
class SubcategoryHelper
{
    private static $_resultArray = array();
    
    /**
     * Возвращает массив данных SubcategoryModel для переданного ID CategoriesModel
     * @param string ID CategoriesModel
     * @return array/null
     */
    public static function getSubcategory($categoryId)
    {
        try {
            if ($data = MappersHelper::getSubcategoryForCategoryList(new CategoriesModel(['id'=>$categoryId]))) {
                self::$_resultArray = ArrayHelper::map($data, 'id', 'name');
            }
            return self::$_resultArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Очищает массив self::$_resultArray
     * @return boolean
     */
    public static function clean()
    {
        try {
            self::$_resultArray = array();
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
