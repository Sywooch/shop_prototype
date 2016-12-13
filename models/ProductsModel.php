<?php

namespace app\models;

use app\models\{AbstractBaseModel,
    CategoriesModel,
    SizesModel,
    SubcategoryModel};
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'products';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект CategoriesModel
     * @return ActiveQueryInterface
     */
    public function getCategory()
    {
        try {
            return $this->hasOne(CategoriesModel::class, ['id'=>'id_category'])->inverseOf('products');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SubcategoryModel
     * @return ActiveQueryInterface
     */
    public function getSubcategory()
    {
        try {
            return $this->hasOne(SubcategoryModel::class, ['id'=>'id_subcategory'])->inverseOf('products');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив ColorsModel
     * @return ActiveQueryInterface
     */
    public function getColors()
    {
        try {
            return $this->hasMany(ColorsModel::class, ['id'=>'id_color'])->viaTable('products_colors', ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив SizesModel
     * @return ActiveQueryInterface
     */
    public function getSizes()
    {
        try {
            return $this->hasMany(SizesModel::class, ['id'=>'id_size'])->viaTable('products_sizes', ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
