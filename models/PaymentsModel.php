<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы payments
 */
class PaymentsModel extends AbstractBaseModel
{
    /**
     * Сценарий удаления типа доставки
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания типа доставки
     */
    const CREATE = 'create';
    /**
     * Сценарий редактирования способа доставки
     */
    const EDIT = 'edit';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'payments';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'description', 'active'],
            self::EDIT=>['id', 'name', 'description', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['name', 'description'], 'required', 'on'=>self::CREATE],
            [['active'], 'default', 'value'=>0, 'on'=>self::CREATE],
            [['id', 'name', 'description'], 'required', 'on'=>self::EDIT],
        ];
    }
}
