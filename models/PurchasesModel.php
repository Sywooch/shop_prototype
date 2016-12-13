<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\{ColorsModel,
    ProductsModel,
    SizesModel};

/**
 * Представляет данные таблицы purchases
 */
class PurchasesModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'purchases';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект ProductsModel
     * @return ActiveQueryInterface
     */
    public function getProduct()
    {
        try {
            return $this->hasOne(ProductsModel::class, ['id'=>'id_product']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект ColorsModel
     * @return ActiveQueryInterface
     */
    public function getColor()
    {
        try {
            return $this->hasOne(ColorsModel::class, ['id'=>'id_color']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SizesModel
     * @return ActiveQueryInterface
     */
    public function getSize()
    {
        try {
            return $this->hasOne(SizesModel::class, ['id'=>'id_size']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
