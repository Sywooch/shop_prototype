<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseModel,
    CategoriesModel,
    SizesModel,
    SubcategoryModel};
use app\helpers\TransliterationHelper;
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
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'total_products', 'seocode'],
            self::GET_FROM_FORM=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'total_products', 'seocode'],
        ];
    }
    
    /**
     * Получает объект CategoriesModel, с которой связан текущий объект ProductsModel
     * @return object CategoriesModel
     */
    public function getCategories()
    {
        try {
            return $this->hasOne(CategoriesModel::className(), ['id'=>'id_category'])->inverseOf('products');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SubcategoryModel, с которой связан текущий объект ProductsModel
     * @return object SubcategoryModel
     */
    public function getSubcategory()
    {
        try {
            return $this->hasOne(SubcategoryModel::className(), ['id'=>'id_subcategory']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив ColorsModel, с которыми связан текущий объект ProductsModel
     * @return array ColorsModel
     */
    public function getColors()
    {
        try {
            return $this->hasMany(ColorsModel::className(), ['id'=>'id_color'])->viaTable('products_colors', ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив SizesModel, с которыми связан текущий объект ProductsModel
     * @return array SizesModel
     */
    public function getSizes()
    {
        try {
            return $this->hasMany(SizesModel::className(), ['id'=>'id_size'])->viaTable('products_sizes', ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
