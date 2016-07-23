<?php

namespace app\queries;

use app\queries\AbstractFiltersQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class BrandsJoinProductsQueryCreator extends AbstractFiltersQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $config = [
        'tableOne'=>[ # Данные для выборки из таблицы products_brands
            'firstTableName'=>'brands',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_brands',
            'secondTableFieldOn'=>'id_brands',
        ],
        'tableTwo'=>[ # Данные для выборки из таблицы products
            'firstTableName'=>'products_brands',
            'firstTableFieldOn'=>'id_products',
            'secondTableName'=>'products',
            'secondTableFieldOn'=>'id',
        ],
    ];
    
    public function init()
    {
        parent::init();
        
        $this->categoriesArrayFilters = array_merge($this->categoriesArrayFilters, $this->config);
    }
}
