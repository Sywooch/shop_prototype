<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\SubcategoryModel;

/**
 * Коллекция методов для обработки данных, 
 * связанных с таблицей subcategory
 */
class SubcategoryHelper
{
    /**
     * Получает данные из БД, конструирует массив, 
     * представляющий все строки таблицы subcategory в формате пар ключ-значение, 
     * где одно из полей станет ключем, а второе значение
     * @params string $idCategory id категории, 
     * с которой должны быть связаны получаемые данные
     * @params string $fieldKey поле, которое станет ключем
     * @params string $fieldKey поле, которое станет значением
     * @return array
     */
    public static function forCategoryMap(int $idCategory, string $fieldKey, string $fieldValue): array
    {
        try {
            $subcategoryQuery = SubcategoryModel::find();
            $subcategoryQuery->extendSelect([$fieldKey, $fieldValue]);
            $subcategoryQuery->where(['[[subcategory.id_category]]'=>$idCategory]);
            $subcategoryQuery->orderBy(['[[subcategory.' . $fieldValue . ']]'=>SORT_ASC]);
            $subcategoryArray = $subcategoryQuery->all();
            if (!$subcategoryArray[0] instanceof SubcategoryModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'SubcategoryModel']));
            }
            
            return ArrayHelper::map($subcategoryArray, $fieldKey, $fieldValue);
        } catch (\Throwable $t) {
           ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
