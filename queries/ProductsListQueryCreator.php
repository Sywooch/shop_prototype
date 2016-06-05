<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

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
            'secondTableFieldWhere'=>'color',
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
            'secondTableFieldWhere'=>'size',
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
            'secondTableFieldWhere'=>'brand',
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса, выбирая сценарий на основе данных из объекта Yii::$app->request
     */
    public function getSelectQuery()
    {
        try {
            $getKeys = array_keys(\Yii::$app->request->get());
            if (in_array(\Yii::$app->params['categoryKey'], $getKeys) && !in_array(\Yii::$app->params['subCategoryKey'], $getKeys)) {
                $this->queryForCategory();
            } else if (in_array( \Yii::$app->params['categoryKey'], $getKeys) && in_array(\Yii::$app->params['subCategoryKey'], $getKeys)) {
                $this->queryForSubCategory();
            } else {
                $this->queryForAll();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД
     * @return string
     */
    protected function queryForAll()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->addFilters();
            $this->addSelectEnd();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует строку запроса к БД, фильруя по категории
     * @return string
     */
    protected function queryForCategory()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->addFilters();
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['categoryKey']
            );
            $this->addSelectEnd();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        $this->_mapperObject->categoryFlag = true;
    }
    
    /**
     * Формирует строку запроса к БД, фильруя по подкатегории
     * @return string
     */
    protected function queryForSubCategory()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->addFilters();
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['categoryKey']
            );
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldWhere'],
                \Yii::$app->params['subCategoryKey']
            );
            $this->addSelectEnd();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        $this->_mapperObject->categoryFlag = true;
        $this->_mapperObject->subcategoryFlag = true;
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     */
    protected function addSelectHead()
    {
        try {
            $this->_mapperObject->query = 'SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->_mapperObject->query .= $this->addOtherFields();
            $this->_mapperObject->query .= $this->addTableName();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldOn']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует финальную часть строки запроса к БД
     */
    protected function addSelectEnd()
    {
        try {
            $this->addOrder();
            $this->_mapperObject->query .= $this->addLimit();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую фильтры
     */
    protected function addFilters()
    {
        try {
            $getArrayKeys = array_keys(\Yii::$app->request->get());
            
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $this->_mapperObject->query .= $this->getJoin(
                        $this->categoriesArrayFilters[$this->_mapperObject->tableName . '_' . $filter]['firstTableName'],
                        $this->categoriesArrayFilters[$this->_mapperObject->tableName . '_' . $filter]['firstTableFieldOn'],
                        $this->categoriesArrayFilters[$this->_mapperObject->tableName . '_' . $filter]['secondTableName'],
                        $this->categoriesArrayFilters[$this->_mapperObject->tableName . '_' . $filter]['secondTableFieldOn']
                    );
                    $this->_mapperObject->query .= $this->getJoin(
                        $this->categoriesArrayFilters[$filter]['firstTableName'],
                        $this->categoriesArrayFilters[$filter]['firstTableFieldOn'],
                        $this->categoriesArrayFilters[$filter]['secondTableName'],
                        $this->categoriesArrayFilters[$filter]['secondTableFieldOn']
                    );
                    $this->_mapperObject->filtersArray[':' . $filter] = \Yii::$app->request->get($filter);
                }
            }
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $this->_mapperObject->query .= $this->getWhere(
                        $this->categoriesArrayFilters[$filter]['secondTableName'],
                        $this->categoriesArrayFilters[$filter]['secondTableFieldWhere'],
                        $filter
                    );
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        $this->_mapperObject->filtersFlag = true;
    }
    
    /**
     * Формирует часть запроса к БД, задающую порядок сортировки
     * @return string
     */
    private function addOrder()
    {
        try {
            if (!isset($this->_mapperObject->orderByField)) {
                throw new ErrorException('Не задано имя столбца для сортировки!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        $this->_mapperObject->query .= ' ORDER BY [[' . $this->_mapperObject->tableName . '.' . $this->_mapperObject->orderByField . ']] ' . $this->_mapperObject->orderByType;
    }
    
    /**
     * Формирует часть запроса к БД, ограничивающую выборку
     * @return string
     */
    private function addLimit()
    {
        try {
            if (in_array(\Yii::$app->params['pagePointer'], array_keys(\Yii::$app->request->get()))) {
                return ' LIMIT ' . (\Yii::$app->request->get(\Yii::$app->params['pagePointer']) * $this->_mapperObject->limit) . ', ' . $this->_mapperObject->limit;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return ' LIMIT 0, ' . $this->_mapperObject->limit;
    }
}