<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы subcategory
 */
class SubcategoryModel extends AbstractBaseModel
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
            self::CREATE=>['name', 'seocode', 'id_category', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['name', 'seocode', 'id_category'], 'required', 'on'=>self::CREATE],
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
            return 'subcategory';
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
            return $this->hasOne(CategoriesModel::class, ['id'=>'id_category'])->inverseOf('subcategory');
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
            return $this->hasMany(ProductsModel::class, ['id_subcategory'=>'id'])->inverseOf('subcategory');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
