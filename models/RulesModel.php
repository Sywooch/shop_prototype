<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы users
 */
class RulesModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы
    */
    const GET_FROM_DB = 'getFromDb';
    
    public $id = '';
    public $rule = '';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'rule'],
        ];
    }
}
