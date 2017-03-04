<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы deliveries
 */
class DeliveriesModel extends AbstractBaseModel
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
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'deliveries';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'description', 'price', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['name', 'description', 'price'], 'required', 'on'=>self::CREATE],
            [['active'], 'default', 'value'=>0, 'on'=>self::CREATE],
        ];
    }
}
