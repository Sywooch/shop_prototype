<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Представляет данные таблицы categories
 */
class CategoriesModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'categories';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'seocode'],
            self::GET_FROM_FORM=>['id', 'name', 'seocode'],
        ];
    }
    
    /**
     * Получает массив SubcategoryModel, с которыми связан текущий объект CategoriesModel
     * @return array SubcategoryModel
     */
    public function getSubcategory()
    {
        try {
            return $this->hasMany(SubcategoryModel::className(), ['id_category'=>'id'])->inverseOf('categories');
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
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
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
