<?php

namespace app\queries;

use app\queries\AbstractSeletcForAnythingQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ColorsForProductQueryCreator extends AbstractSeletcForAnythingQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'id'=>[
            'firstTableName'=>'colors',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_colors',
            'secondTableFieldOn'=>'id_colors',
            'secondTableFieldWhere'=>'id_products',
        ],
    ];
}
