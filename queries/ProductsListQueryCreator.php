<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;
use yii\helpers\ArrayHelper;
use yii\base\ErrorException;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsListQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных с учетом категории или(и) подкатегории, а также фильтров
     */
    public $categoriesArrayFilters = [
        'categories'=>[ # Данные для выборки из таблицы categories
            'firstTableName'=>'products', # Имя первой таблицы участвующей в объединении
            'firstTableFieldOn'=>'id_categories', # Имя поля первой таблицы, по которому проходит объединение
            'secondTableName'=>'categories', # Имя второй таблицы участвующей в объединении
            'secondTableFieldOn'=>'id', # Имя поля второй таблицы, по которому проходит объединение
            'secondTableFieldWhere'=>'seocode', # Имя поля второй таблицы, по которому делается выборка с помощью WHERE
        ],
        'subcategory'=>[ # Данные для выборки из таблицы subcategory
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id_subcategory',
            'secondTableName'=>'subcategory',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'seocode',
        ],
        'products_colors'=>[ # Данные для выборки из таблицы products_colors
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_colors',
            'secondTableFieldOn'=>'id_products',
        ],
        'colors'=>[ # Данные для выборки из таблицы colors
            'firstTableName'=>'products_colors',
            'firstTableFieldOn'=>'id_colors',
            'secondTableName'=>'colors',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
        'products_sizes'=>[ # Данные для выборки из таблицы products_sizes
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_sizes',
            'secondTableFieldOn'=>'id_products',
        ],
        'sizes'=>[ # Данные для выборки из таблицы sizes
            'firstTableName'=>'products_sizes',
            'firstTableFieldOn'=>'id_sizes',
            'secondTableName'=>'sizes',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
        'products_brands'=>[ # Данные для выборки из таблицы products_brands
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_brands',
            'secondTableFieldOn'=>'id_products',
        ],
        'brands'=>[ # Данные для выборки из таблицы brands
            'firstTableName'=>'products_brands',
            'firstTableFieldOn'=>'id_brands',
            'secondTableName'=>'brands',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'id',
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса, выбирая сценарий на основе данных из объекта Yii::$app->request
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            if (!$this->addSelectHead()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            if (!$this->addFilters()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            if (in_array(\Yii::$app->params['categoryKey'], array_keys(\Yii::$app->request->get()))) {
                if (!$this->queryForCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
            } 
            if (in_array(\Yii::$app->params['subCategoryKey'], array_keys(\Yii::$app->request->get()))) {
                if (!$this->queryForSubCategory()) {
                    throw new ErrorException('Ошибка при построении запроса!');
                }
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
     * Формирует строку запроса к БД, фильруя по категории
     * @return boolean
     */
    protected function queryForCategory()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['categoryKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $this->_mapperObject->params[':' . \Yii::$app->params['categoryKey']] = \Yii::$app->request->get(\Yii::$app->params['categoryKey']);
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, фильруя по подкатегории
     * @return boolean
     */
    protected function queryForSubCategory()
    {
        try {
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            $where = $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['subCategoryKey']
            );
            if (!is_string($where)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $where;
            
            $this->_mapperObject->params[':' . \Yii::$app->params['subCategoryKey']] = \Yii::$app->request->get(\Yii::$app->params['subCategoryKey']);
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     * @return true
     */
    protected function addSelectHead()
    {
        try {
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не поределен categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не поределен subCategoryKey!');
            }
            
            $this->_mapperObject->query = 'SELECT ';
            
            $fields = $this->addFields();
            if (!is_string($fields)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $fields;
            
            $otherFields = $this->addOtherFields();
            if (!is_string($otherFields)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $otherFields;
            
            $name = $this->addTableName();
            if (!is_string($name)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $name;
            
            $join = $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            
            $join = $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldOn']
            );
            if (!is_string($join)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $join;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует финальную часть строки запроса к БД
     * @return boolean
     */
    protected function addSelectEnd()
    {
        try {
            if (!$this->addOrder()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $limit = $this->addLimit();
            if (!$limit) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $limit;
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
            if (empty(\Yii::$app->params['filterKeys'])) {
                throw new ErrorException('Не поределен filterKeys!');
            }
            if (empty(\Yii::$app->params['idKey'])) {
                throw new ErrorException('Не поределен idKey!');
            }
            
            $getArrayKeys = array_keys(array_filter(\Yii::$app->filters->attributes));
            $filtersKeys = array();
            
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $join = $this->getJoin(
                        $this->categoriesArrayFilters[$this->_mapperObject->tableName . '_' . $filter]['firstTableName'],
                        $this->categoriesArrayFilters[$this->_mapperObject->tableName . '_' . $filter]['firstTableFieldOn'],
                        $this->categoriesArrayFilters[$this->_mapperObject->tableName . '_' . $filter]['secondTableName'],
                        $this->categoriesArrayFilters[$this->_mapperObject->tableName . '_' . $filter]['secondTableFieldOn']
                    );
                    if (!is_string($join)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $join;
            
                    $join = $this->getJoin(
                        $this->categoriesArrayFilters[$filter]['firstTableName'],
                        $this->categoriesArrayFilters[$filter]['firstTableFieldOn'],
                        $this->categoriesArrayFilters[$filter]['secondTableName'],
                        $this->categoriesArrayFilters[$filter]['secondTableFieldOn']
                    );
                    if (!is_string($join)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $join;
                    
                    $filterData = \Yii::$app->filters->$filter;
                    foreach ($filterData as $key=>$val) {
                        $filterKey = $key . $filter . '_' . \Yii::$app->params['idKey'];
                        $this->_mapperObject->params[':' . $filterKey] = $val;
                        $filtersKeys[$filter][] = $filterKey;
                    }
                }
            }
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $where = $this->getWhereIn(
                        $this->categoriesArrayFilters[$filter]['secondTableName'],
                        $this->categoriesArrayFilters[$filter]['secondTableFieldWhere'],
                        implode(',:', $filtersKeys[$filter])
                    );
                    if (!is_string($where)) {
                        throw new ErrorException('Ошибка при построении запроса!');
                    }
                    $this->_mapperObject->query .= $where;
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, задающую порядок сортировки
     * @return boolean
     */
    private function addOrder()
    {
        try {
            if (empty($this->_mapperObject->orderByField)) {
                throw new ErrorException('Не задано имя столбца для сортировки!');
            }
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            if (empty($this->_mapperObject->orderByType)) {
                throw new ErrorException('Не задан тип сортировки!');
            }
            
            $this->_mapperObject->query .= ' ORDER BY [[' . $this->_mapperObject->tableName . '.' . $this->_mapperObject->orderByField . ']] ' . $this->_mapperObject->orderByType;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, ограничивающую выборку
     * @return string
     */
    private function addLimit()
    {
        try {
            if (empty(\Yii::$app->params['pagePointer'])) {
                throw new ErrorException('Не поределен pagePointer!');
            }
            if (empty($this->_mapperObject->limit)) {
                throw new ErrorException('Отсутствуют данные для построения запроса!');
            }
            
            if (in_array(\Yii::$app->params['pagePointer'], array_keys(\Yii::$app->request->get()))) {
                return ' LIMIT ' . (\Yii::$app->request->get(\Yii::$app->params['pagePointer']) * $this->_mapperObject->limit) . ', ' . $this->_mapperObject->limit;
            }
            return ' LIMIT 0, ' . $this->_mapperObject->limit;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
