<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseModel,
    CategoriesModel,
    SubcategoryModel};
use app\helpers\TransliterationHelper;

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * @var string seocode текущей записи
     */
    private $_seocode = '';
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'products';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'total_products'],
            self::GET_FROM_FORM=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'total_products'],
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
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
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
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает seocod товара, основываясь на данных СУБД, или 
     * конструируя его из данных поля name
     * return string
     */
     public function getSeocode()
     {
         try {
             if (!empty($this->name)) {
                $this->_seocode = TransliterationHelper::getTransliterationSeparate($this->name);
            }
            return $this->_seocode;
         } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
     }
}
