<?php

namespace app\queries;

use app\queries\AbstractSeletcForAnythingQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class BrandsForProductQueryCreator extends AbstractSeletcForAnythingQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'id'=>[
            'firstTableName'=>'brands',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_brands',
            'secondTableFieldOn'=>'id_brands',
            'secondTableFieldWhere'=>'id_products',
        ],
    ];
}
