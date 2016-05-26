<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class SubcategoryQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'categories'=>[ # Данные для выборки из таблицы categories
            'firstTableName'=>'subcategory', # Имя первой таблицы участвующей в объединении
            'firstTableFieldOn'=>'id_categories', # Имя поля первой таблицы, по которому проходит объединение
            'secondTableName'=>'categories', # Имя второй таблицы участвующей в объединении
            'secondTableFieldOn'=>'id', # Имя поля второй таблицы, по которому проходит объединение
            'secondTableFieldWhere'=>'id', # Имя поля второй таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            $this->_mapperObject->query = 'SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->addTableName();
            $this->_mapperObject->query .= $this->getJoin(\Yii::$app->params['categoryKey']);
            $this->_mapperObject->query .= $this->getWhere(\Yii::$app->params['categoryKey']);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
