<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы colors
 */
class ColorsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы добавления товара
    */
    const GET_FROM_ADD_PRODUCT = 'getFromAddProduct';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'colors';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ADD_PRODUCT=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['color'], 'app\validators\StripTagsValidator'],
            [['id'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT],
        ];
    }
    
    /**
     * Получает данные из БД, конструирует массив, 
     * представляющий все строки таблицы colors в формате пар ключ-значение, 
     * где одно из полей станет ключем, а второе значение
     * @params string $fieldKey поле, которое станет ключем
     * @params string $fieldKey поле, которое станет значением
     * @return array
     */
    public static function allMap(string $fieldKey, string $fieldValue): array
    {
        try {
            $colorsQuery = self::find();
            $colorsQuery->extendSelect([$fieldKey, $fieldValue]);
            $colorsQuery->orderBy(['[[colors.' . $fieldValue . ']]'=>SORT_ASC]);
            
            $colorsArray = $colorsQuery->all();
            if (!$colorsArray[0] instanceof self) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>__CLASS__]));
            }
            
            return ArrayHelper::map($colorsArray, $fieldKey, $fieldValue);
        } catch (\Throwable $t) {
           ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
