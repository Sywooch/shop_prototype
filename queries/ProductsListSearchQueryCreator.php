<?php

namespace app\queries;

use app\queries\ProductsListQueryCreator;
use yii\base\ErrorException;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListSearchQueryCreator extends ProductsListQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $config = [
        'search'=>[ # Данные для выборки из таблицы categories
            'tableName'=>'products', # Имя таблицы участвующей в объединении
            'tableFieldWhere'=>'description', # Имя поля таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
    
    public function init()
    {
        parent::init();
        
        $this->categoriesArrayFilters = array_merge($this->categoriesArrayFilters, $this->config);
    }
    
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (!$this->addSelectHead()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->addFilters()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->addSelectEnd()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую фильтры
     * @return boolean
     */
    protected function addFilters()
    {
        try {
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не поределен searchKey!');
            }
            
            if (!parent::addFilters()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (\Yii::$app->request->get(\Yii::$app->params['searchKey'])) {
                $where = $this->getWhereLike(
                    $this->categoriesArrayFilters[\Yii::$app->params['searchKey']]['tableName'],
                    $this->categoriesArrayFilters[\Yii::$app->params['searchKey']]['tableFieldWhere'],
                    \Yii::$app->params['searchKey']
                );
                if (!is_string($where)) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
                $this->_mapperObject->query .= $where;
                
                $this->_mapperObject->params[':' . \Yii::$app->params['searchKey']] = '%' . \Yii::$app->request->get(\Yii::$app->params['searchKey']) . '%';
                return true;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
