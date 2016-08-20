<?php

namespace app\queries;

use app\queries\{AbstractFiltersClientQueryCreator,
    ProductsListQueryCreator};

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ColorsJoinProductsClientQueryCreator extends AbstractFiltersClientQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'tableOne'=>[
            'firstTableName'=>'colors',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_colors',
            'secondTableFieldOn'=>'id_colors',
        ],
        'tableTwo'=>[
            'firstTableName'=>'products_colors',
            'firstTableFieldOn'=>'id_products',
            'secondTableName'=>'products',
            'secondTableFieldOn'=>'id',
        ],
        'active'=>[
            'tableName'=>'products',
            'tableFieldWhere'=>'active',
        ],
    ];
}
