<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\SizesModel;

/**
 * Коллекция методов для обработки данных, 
 * связанных с таблицей sizes
 */
class SizesHelper
{
    /**
     * Получает данные из БД, конструирует массив, 
     * представляющий все строки таблицы sizes в формате пар ключ-значение, 
     * где одно из полей станет ключем, а второе значение
     * @params string $fieldKey поле, которое станет ключем
     * @params string $fieldKey поле, которое станет значением
     * @return array
     */
    public static function allMap(string $fieldKey, string $fieldValue): array
    {
        try {
            $sizesQuery = SizesModel::find();
            $sizesQuery->extendSelect([$fieldKey, $fieldValue]);
            $sizesQuery->orderBy(['[[sizes.'. $fieldValue . ']]'=>SORT_ASC]);
            
            $sizesArray = $sizesQuery->all();
            if (!$sizesArray[0] instanceof SizesModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'SizesModel']));
            }
            
            return ArrayHelper::map($sizesArray, $fieldKey, $fieldValue);
        } catch (\Throwable $t) {
           ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
