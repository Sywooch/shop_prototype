<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\BrandsModel;

/**
 * Коллекция методов для обработки данных, 
 * связанных с таблицей brands
 */
class BrandsHelper
{
    /**
     * Получает данные из БД, конструирует массив, 
     * представляющий все строки таблицы brands в формате пар ключ-значение, 
     * где одно из полей станет ключем, а второе значение
     * @params string $fieldKey поле, которое станет ключем
     * @params string $fieldKey поле, которое станет значением
     * @return array
     */
    public static function allMap(string $fieldKey, string $fieldValue): array
    {
        try {
            $brandsQuery = BrandsModel::find();
            $brandsQuery->extendSelect([$fieldKey, $fieldValue]);
            $brandsQuery->orderBy(['[[brands.'. $fieldValue . ']]'=>SORT_ASC]);
            
            $brandsArray = $brandsQuery->all();
            if (!$brandsArray[0] instanceof BrandsModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'BrandsModel']));
            }
            
            return ArrayHelper::map($brandsArray, $fieldKey, $fieldValue);
        } catch (\Throwable $t) {
           ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
