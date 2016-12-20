<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы фильтров для каталога товаров
 */
class PurchaseForm extends AbstractBaseForm
{
    /**
     * Сценарий добавления товара в корзину
     */
    const GET = 'get';
    
    /**
     * @var int количество единиц товара
     */
    public $quantity;
    /**
     * @var int Iцвет товара
     */
    public $id_color;
    /**
     * @var int размер товара
     */
    public $id_size;
    /**
     * @var int ID товара
     */
    public $id_product;
    /**
     * @var float стоимость товара
     */
    public $price;
    
    public function scenarios()
    {
        return [
            self::GET=>['quantity', 'id_color', 'id_size', 'id_product', 'price']
        ];
    }
    
    public function rules()
    {
        return [
            [['quantity', 'id_color', 'id_size', 'id_product', 'price'], 'required', 'on'=>self::GET]
        ];
    }
}