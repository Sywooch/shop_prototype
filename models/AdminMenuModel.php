<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы admin_menu
 */
class AdminMenuModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
     */
    const GET_FROM_DB = 'getFromDb';
    
    public $id;
    public $name;
    public $route;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'route'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name'], 'app\validators\StripTagsValidator'],
        ];
    }
}
