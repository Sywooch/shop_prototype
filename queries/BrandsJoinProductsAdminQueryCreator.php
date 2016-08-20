<?php

namespace app\queries;

use app\queries\{AbstractFiltersAdminQueryCreator,
    ProductsListQueryCreator};

/**
 * Конструирует запрос к БД для получения списка строк
 */
class BrandsJoinProductsAdminQueryCreator extends AbstractFiltersAdminQueryCreator
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
    ];
}
