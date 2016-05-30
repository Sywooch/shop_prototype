<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ColorsForProductQueryCreator extends AbstractSeletcQueryCreator
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
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            parent::getSelectQuery();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
