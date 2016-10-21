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
     * @return array SubcategoryModel
     */
    public function getSubcategory()
    {
        try {
            return $this->hasMany(SubcategoryModel::className(), ['id_category'=>'id'])->inverseOf('categories');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
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
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает данные из БД, конструирует массив, 
     * представляющий все строки таблицы categories в формате пар ключ-значение, 
     * где одно из полей станет ключем, а второе значение
     * @params string $fieldKey поле, которое станет ключем
     * @params string $fieldKey поле, которое станет значением
     * @return array
     */
    public static function allMap(string $fieldKey, string $fieldValue): array
    {
        try {
            $categoriesQuery = self::find();
            $categoriesQuery->extendSelect([$fieldKey, $fieldValue]);
            $categoriesQuery->orderBy(['[[categories.' . $fieldValue . ']]'=>SORT_ASC]);
            
            $categoriesArray = $categoriesQuery->all();
            if (!$categoriesArray[0] instanceof self) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>__CLASS__]));
            }
            
            return ArrayHelper::map($categoriesArray, $fieldKey, $fieldValue);
        } catch (\Throwable $t) {
           ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
