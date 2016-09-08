<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы subcategory
 */
class SubcategoryModel extends AbstractBaseModel
{
    /**
     * @var string имя таблицы, связанной с текущим классом AR
     */
    public static $_tableName = 'subcategory';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'seocode', 'id_categories'],
            self::GET_FROM_FORM=>['id', 'name', 'seocode', 'id_categories'],
        ];
    }
    
    /**
     * Получает объект CategoriesModel, с которой связан текущий объект SubcategoryModel
     * @return object CategoriesModel
     */
    public function getCategories()
    {
        try {
            return $this->hasOne(CategoriesModel::className(), ['id'=>'id_categories'])->inverseOf('subcategory');
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
