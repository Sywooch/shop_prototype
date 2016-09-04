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
    
    /**
     * Получает объект категории, с которой связан текущий объект SubcategoryModel
     * @return object CategoriesModel
     */
    public function getCategories()
    {
        try {
            return $this->hasOne(CategoriesModel::className(), ['id'=>'id_categories'])->reverseOf('subcategory');
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
