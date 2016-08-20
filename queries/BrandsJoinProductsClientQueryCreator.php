<?php

namespace app\queries;

use app\queries\{AbstractFiltersClientQueryCreator,
    ProductsListQueryCreator};

/**
 * Конструирует запрос к БД для получения списка строк
 */
class BrandsJoinProductsClientQueryCreator extends AbstractFiltersClientQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'tableOne'=>[
            'firstTableName'=>'brands',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_brands',
            'secondTableFieldOn'=>'id_brands',
        ],
        'tableTwo'=>[
            'firstTableName'=>'products_brands',
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
