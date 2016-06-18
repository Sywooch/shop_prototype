<?php

namespace app\queries;

use app\queries\AbstractFiltersQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ColorsQueryCreator extends AbstractFiltersQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $config = [
        'tableOne'=>[ # Данные для выборки из таблицы products_colors
            'firstTableName'=>'colors',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_colors',
            'secondTableFieldOn'=>'id_colors',
        ],
        'tableTwo'=>[ # Данные для выборки из таблицы products
            'firstTableName'=>'products_colors',
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
