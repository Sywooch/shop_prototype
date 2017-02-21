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
     * Сценарий удаления категории
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания категории
     */
    const CREATE = 'create';
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'seocode', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['name', 'seocode'], 'required', 'on'=>self::CREATE],
            [['active'], 'default', 'value'=>0, 'on'=>self::CREATE],
        ];
    }
    
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
