<?php

namespace app\models;

use app\models\{AbstractBaseModel,
    BrandsModel,
    CategoriesModel,
    RelatedProductsModel,
    SizesModel,
    SubcategoryModel};
use app\exceptions\ExceptionsTrait;
use app\validators\AddProductSeocodeValidator;

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * Сценарий фиксации просмотров товара
     */
    const VIEWS = 'views';
    /**
     * Сценарий сохранения данных
     */
    const SAVE = 'save';
    /**
     * Сценарий обновления данных
     */
    const EDIT = 'edit';
    /**
     * Сценарий удаления данных
     */
    const DELETE = 'delete';
    
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
            self::VIEWS=>['views'],
            self::SAVE=>['date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'active', 'total_products', 'seocode'],
            self::EDIT=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'active', 'total_products', 'seocode', 'views'],
            self::DELETE=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['views'], 'required', 'on'=>self::VIEWS],
            [['date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'seocode'], 'required', 'on'=>self::SAVE],
            [['active', 'total_products', 'views'], 'default', 'value'=>0, 'on'=>self::SAVE],
            [['seocode'], AddProductSeocodeValidator::class, 'on'=>self::SAVE],
            [['description', 'short_description', 'images'], 'default', 'value'=>'', 'on'=>self::EDIT],
            [['total_products', 'views'], 'default', 'value'=>0, 'on'=>self::EDIT],
            [['id', 'code', 'name', 'price', 'id_category', 'id_subcategory', 'id_brand', 'seocode'], 'required', 'on'=>self::EDIT],
            [['id'], 'required', 'on'=>self::DELETE],
        ];
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
    
    /**
     * Получает BrandsModel
     * @return ActiveQueryInterface
     */
    public function getBrand()
    {
        try {
            return $this->hasOne(BrandsModel::class, ['id'=>'id_brand']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив RelatedProductsModel
     * @return ActiveQueryInterface
     */
    public function getRelated()
    {
        try {
            return $this->hasMany(RelatedProductsModel::class, ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
