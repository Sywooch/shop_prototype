<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseModel,
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
    
    /**
     * Получает массив объектов подкатегорий, с которыми связан текущий объект CategoriesModel
     * @return array SubcategoryModel
     */
    public function getSubcategory()
    {
        try {
            return $this->hasMany(SubcategoryModel::className(), ['id_categories'=>'id']);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
