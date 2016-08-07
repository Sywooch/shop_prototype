<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsByIdQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'id'=>[ # Данные для выборки из таблицы products
            'tableName'=>'products', # Имя таблицы участвующей в объединении
            'tableFieldWhere'=>'id', # Имя поля таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не поределен idKey!');
            }
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['tableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['tableFieldWhere'],
                \Yii::$app->params['idKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
