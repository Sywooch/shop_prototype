<?php

namespace app\queries;

use app\queries\AbstractFiltersQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SizesQueryCreator extends AbstractFiltersQueryCreator
{
    public function init()
    {
        parent::init();
        
        $config = [
            'tableOne'=>[ # Данные для выборки из таблицы products_sizes
                'firstTableName'=>'sizes',
                'firstTableFieldOn'=>'id',
                'secondTableName'=>'products_sizes',
                'secondTableFieldOn'=>'id_sizes',
            ],
            'tableTwo'=>[ # Данные для выборки из таблицы products
                'firstTableName'=>'products_sizes',
                'firstTableFieldOn'=>'id_products',
                'secondTableName'=>'products',
                'secondTableFieldOn'=>'id',
            ],
        ];
        $this->categoriesArrayFilters = array_merge($this->categoriesArrayFilters, $config);
    }
}
