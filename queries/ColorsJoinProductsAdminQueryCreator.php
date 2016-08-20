<?php

namespace app\queries;

use app\queries\{AbstractFiltersAdminQueryCreator,
    ProductsListQueryCreator};

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ColorsJoinProductsAdminQueryCreator extends AbstractFiltersAdminQueryCreator
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
    ];
}
