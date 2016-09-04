<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseModel,
    CategoriesModel,
    SubcategoryModel};

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'products';
    
    /**
     * Получает объект категории, с которой связан текущий объект ProductsModel
     * @return object CategoriesModel
     */
    public function getCategories()
    {
        try {
            return $this->hasOne(CategoriesModel::className(), ['id'=>'id_categories']);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект подкатегории, с которой связан текущий объект ProductsModel
     * @return object SubcategoryModel
     */
    public function getSubcategory()
    {
        try {
            return $this->hasOne(SubcategoryModel::className(), ['id'=>'id_subcategory']);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
