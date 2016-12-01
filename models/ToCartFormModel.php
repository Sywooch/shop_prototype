<?php

namespace app\models;

use yii\base\{ErrorException,
    Model};
use app\models\{AbstractFormModel,
    FormInterface,
    PurchasesModel};

/**
 * Представляет данные формы добавления товара в корзину
 */
class ToCartFormModel extends AbstractFormModel implements FormInterface
{
    /**
     * Сценарий добавления товара в корзину
    */
    const TO_CART = 'toCart';
    
    public $id_product;
    public $quantity;
    public $id_color;
    public $id_size;
    public $price;
    
    public function scenarios()
    {
        return [
            self::TO_CART=>['id_product', 'quantity', 'id_color', 'id_size', 'price'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id_product', 'quantity', 'id_color', 'id_size', 'price'], 'app\validators\StripTagsValidator'],
            [['id_product', 'quantity', 'id_color', 'id_size', 'price'], 'required', 'on'=>self::TO_CART],
        ];
    }
    
    /**
     * Возвращает объект модели, представляющий таблицу СУБД
     * @param string $name имя класса модели
     * @return Model
     */
    public function getModel($name): Model
    {
        try {
            return \Yii::createObject(array_merge(['class'=>$name], $this->toArray()));
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}