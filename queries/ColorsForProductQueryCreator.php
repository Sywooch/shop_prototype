<?php

namespace app\queries;

use app\queries\AbstractSeletcForProductQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ColorsForProductQueryCreator extends AbstractSeletcForProductQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'id'=>[ # Данные для выборки из таблицы colors
            'firstTableName'=>'colors', # Имя первой таблицы участвующей в объединении
            'firstTableFieldOn'=>'id', # Имя поля первой таблицы, по которому проходит объединение
            'secondTableName'=>'products_colors', # Имя второй таблицы участвующей в объединении
            'secondTableFieldOn'=>'id_colors', # Имя поля второй таблицы, по которому проходит объединение
            'secondTableFieldWhere'=>'id_products', # Имя поля второй таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
}
