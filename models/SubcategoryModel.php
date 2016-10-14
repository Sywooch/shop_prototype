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
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'seocode', 'id_category', 'active'],
            self::GET_FROM_FORM=>['id', 'name', 'seocode', 'id_category', 'active'],
        ];
    }
    
    /**
     * Получает объект CategoriesModel, с которой связан текущий объект SubcategoryModel
     * @return object CategoriesModel
     */
    public function getCategories()
    {
        try {
            return $this->hasOne(CategoriesModel::className(), ['id'=>'id_category'])->inverseOf('subcategory');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив ProductsModel, с которыми связан текущий объект SubcategoryModel
     * @return array ProductsModel
     */
    public function getProducts()
    {
        try {
            return $this->hasMany(ProductsModel::className(), ['id_subcategory'=>'id'])->inverseOf('subcategory');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
