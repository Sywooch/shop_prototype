<?php

namespace app\models;

use app\models\{AbstractBaseModel,
    ProductsModel,
    SubcategoryModel};
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы categories
 */
class CategoriesModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'categories';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив SubcategoryModel, с которыми связан текущий объект CategoriesModel
     * @return array SubcategoryModel
     */
    public function getSubcategory()
    {
        try {
            return $this->hasMany(SubcategoryModel::className(), ['id_category'=>'id'])->inverseOf('categories');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив ProductsModel, с которыми связан текущий объект CategoriesModel
     * @return array ProductsModel
     */
    public function getProducts()
    {
        try {
            return $this->hasMany(ProductsModel::className(), ['id_category'=>'id'])->inverseOf('categories');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
