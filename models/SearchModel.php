<?php

namespace app\models;

use yii\base\Model;

/**
 * Представляет поисковый запрос
 */
class SearchModel extends Model
{
    /**
     * Сценарий загрузки из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * @var string поисковый запрос
     */
    public $text;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['text'],
        ];
    }
}
