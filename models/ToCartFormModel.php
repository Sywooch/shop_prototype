<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractFormModel;

/**
 * Представляет данные формы добавления товара в корзину
 */
class ToCartFormModel extends AbstractFormModel
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
}
