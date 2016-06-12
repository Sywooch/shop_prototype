<?php

namespace app\mappers;

use app\mappers\AbstractProductsListAndFilterMapper;
use yii\helpers\ArrayHelper;

/**
 * Получает строки с данными о категориях из БД, конструирует из каждой строки объект данных
 */
abstract class AbstractFilterMapper extends AbstractProductsListAndFilterMapper
{
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        parent::getData();
        ArrayHelper::multisort($this->DbArray, [$this->orderByField], [SORT_ASC]);
    }
}
