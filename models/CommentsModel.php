<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\{EmailsModel,
    NamesModel,
    ProductsModel};

/**
 * Представляет данные таблицы comments
 */
class CommentsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения нового комментария
     */
    const SAVE = 'save';
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'comments';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::SAVE=>['date', 'text', 'id_name', 'id_email', 'id_product'],
        ];
    }
    
    public function rules()
    {
        return [
            [['date', 'text', 'id_name', 'id_email', 'id_product'], 'required', 'on'=>self::SAVE],
        ];
    }
    
    /**
     * Получает объект NamesModel
     * @return ActiveQueryInterface
     */
    public function getName()
    {
        try {
            return $this->hasOne(NamesModel::class, ['id'=>'id_name']);
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект EmailsModel
     * @return ActiveQueryInterface
     */
    public function getEmail()
    {
        try {
            return $this->hasOne(EmailsModel::class, ['id'=>'id_email']);
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
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
