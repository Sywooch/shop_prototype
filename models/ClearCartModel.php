<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные, полученный из формы для удаления продуктов из корзины
 */
class ClearCartModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $productId;
    public $categories;
    public $subcategory;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['productId', 'categories', 'subcategory'],
        ];
    }
}
