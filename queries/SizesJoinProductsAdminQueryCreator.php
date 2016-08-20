<?php

namespace app\queries;

use app\queries\{AbstractFiltersAdminQueryCreator,
    ProductsListQueryCreator};

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SizesJoinProductsAdminQueryCreator extends AbstractFiltersAdminQueryCreator
{
    /**
     * @var array массив данных для построения запроса
     */
    public $config = [
        'tableOne'=>[
            'firstTableName'=>'sizes',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_sizes',
            'secondTableFieldOn'=>'id_sizes',
        ],
        'tableTwo'=>[
            'firstTableName'=>'products_sizes',
            'firstTableFieldOn'=>'id_products',
            'secondTableName'=>'products',
            'secondTableFieldOn'=>'id',
        ],
    ];
}
