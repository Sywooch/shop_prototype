<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные поиска
 */
class SearchModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    
    public $search;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['search'],
        ];
    }
}
