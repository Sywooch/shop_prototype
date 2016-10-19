<?php

namespace app\models;

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
}
