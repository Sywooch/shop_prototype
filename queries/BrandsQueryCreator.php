<?php

namespace app\queries;

use app\queries\AbstractFiltersQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class BrandsQueryCreator extends AbstractFiltersQueryCreator
{
    public function init()
    {
        parent::init();
        
        $config = [
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
        $this->categoriesArrayFilters = array_merge($this->categoriesArrayFilters, $config);
    }
}
