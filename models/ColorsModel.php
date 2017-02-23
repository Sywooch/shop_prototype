<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы colors
 */
class ColorsModel extends AbstractBaseModel
{
    /**
     * Сценарий удаления цвета
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания цвета
     */
    const CREATE = 'create';
    
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
            self::DELETE=>['id'],
            self::CREATE=>['color'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['color'], 'required', 'on'=>self::CREATE],
        ];
    }
}
