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
     * Получает массив SubcategoryModel
     * @return ActiveQueryInterface
     */
    public function getSubcategory()
    {
        try {
            return $this->hasMany(SubcategoryModel::class, ['id_category'=>'id'])->inverseOf('category');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив ProductsModel
     * @return ActiveQueryInterface
     */
    public function getProducts()
    {
        try {
            return $this->hasMany(ProductsModel::class, ['id_category'=>'id'])->inverseOf('category');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
