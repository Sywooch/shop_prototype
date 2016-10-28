<?php

namespace app\models;

use yii\helpers\ArrayHelper;
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
     * @return ActiveQueryInterface the relational query object
     */
    public function getSubcategory()
    {
        try {
            return $this->hasMany(SubcategoryModel::className(), ['id_category'=>'id'])->inverseOf('category');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив ProductsModel, с которыми связан текущий объект CategoriesModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getProducts()
    {
        try {
            return $this->hasMany(ProductsModel::className(), ['id_category'=>'id'])->inverseOf('category');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
