<?php

namespace app\queries;

use app\queries\AbstractSeletcForAnythingQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SizesForProductQueryCreator extends AbstractSeletcForAnythingQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'id'=>[
            'firstTableName'=>'sizes',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_sizes',
            'secondTableFieldOn'=>'id_sizes',
            'secondTableFieldWhere'=>'id_products',
        ],
    ];
}
