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
    /**
     * Возвращает массив данных SubcategoryModel для переданного ID CategoriesModel
     * @param string ID CategoriesModel
     * @return array/null
     */
    public static function getSubcategory($categoryId)
    {
        try {
            if ($data = MappersHelper::getSubcategoryForCategoryList(new CategoriesModel(['id'=>$categoryId]))) {
                return ArrayHelper::map($data, 'id', 'name');
            }
            return null;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
