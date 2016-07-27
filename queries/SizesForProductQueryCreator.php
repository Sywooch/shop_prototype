<?php

namespace app\queries;

use app\queries\AbstractSeletcForAnythingQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SizesForProductQueryCreator extends AbstractSeletcForAnythingQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'id'=>[ # Данные для выборки из таблицы sizes
            'firstTableName'=>'sizes', # Имя первой таблицы участвующей в объединении
            'firstTableFieldOn'=>'id', # Имя поля первой таблицы, по которому проходит объединение
            'secondTableName'=>'products_sizes', # Имя второй таблицы участвующей в объединении
            'secondTableFieldOn'=>'id_sizes', # Имя поля второй таблицы, по которому проходит объединение
            'secondTableFieldWhere'=>'id_products', # Имя поля второй таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
}
