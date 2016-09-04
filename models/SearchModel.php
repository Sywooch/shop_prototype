<?php

namespace app\models;

use yii\base\Model;
use app\traits\ExceptionsTrait;

/**
 * Представляет данные поиска
 */
class SearchModel extends Model
{
    use ExceptionsTrait;
    
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
