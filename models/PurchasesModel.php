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
     * Сценарий сохранения данных из формы добавления товара в корзину
    */
    const GET_FROM_ADD_TO_CART = 'getFromAddToCart';
    /**
     * Сценарий удаления 1 товара из корзины
    */
    const GET_FROM_DELETE_FROM_CART = 'getFromDeleteFromCart';
    
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
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ADD_TO_CART=>['id_product', 'quantity', 'id_color', 'id_size'],
            self::GET_FROM_DELETE_FROM_CART=>['id_product'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_product', 'quantity', 'id_color', 'id_size'], 'required', 'on'=>self::GET_FROM_ADD_TO_CART],
            [['id_product'], 'required', 'on'=>self::GET_FROM_DELETE_FROM_CART],
        ];
    }
    
    /**
     * Получает объект ProductsModel, с которым связан текущий объект PurchasesModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getProduct()
    {
        try {
            return $this->hasOne(ProductsModel::className(), ['id'=>'id_product']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект ColorsModel, с которым связан текущий объект PurchasesModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getColor()
    {
        try {
            return $this->hasOne(ColorsModel::className(), ['id'=>'id_color']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SizesModel, с которым связан текущий объект PurchasesModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getSize()
    {
        try {
            return $this->hasOne(SizesModel::className(), ['id'=>'id_size']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Выполняет пакетное сохранение
     * @param array $toRecordArray массив исходных данных 
     * @return int
     */
    public static function batchInsert(array $purchasesArray, int $name, int $surname, int $email, int $phone, int $address, int $city, int $country, int $postcode, int $delivery, int $payment, int $user): int
    {
        try {
            $counter = 0;
            
            if (!empty($purchasesArray)) {
                $date = time();
                
                $toRecord = [];
                foreach ($purchasesArray as $purchase) {
                    $toRecord[] = [
                        $user,
                        $name,
                        $surname,
                        $email, 
                        $phone, 
                        $address,
                        $city, 
                        $country, 
                        $postcode,
                        $purchase['id_product'],
                        $purchase['quantity'],
                        $purchase['id_color'],
                        $purchase['id_size'],
                        $delivery,
                        $payment,
                        true,
                        $date,
                    ];
                    ++$counter;
                }
                
                $fields = ['[[id_user]]', '[[id_name]]', '[[id_surname]]', '[[id_email]]', '[[id_phone]]', '[[id_address]]', '[[id_city]]', '[[id_country]]', '[[id_postcode]]', '[[id_product]]', '[[quantity]]', '[[id_color]]', '[[id_size]]', '[[id_delivery]]', '[[id_payment]]', '[[received]]', '[[received_date]]'];
                
                if (!\Yii::$app->db->createCommand()->batchInsert('{{purchases}}', $fields, $toRecord)->execute()) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'PurchsesModel::batchInsert']));
                }
            }
            
            return $counter;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
