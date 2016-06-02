<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;
use yii\helpers\ArrayHelper;

/**
 * Формирует строку запроса к БД
 */
class SimilarProductsQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * @var array массив для выборки данных
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
    ];
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            $this->_mapperObject->query = 'SELECT DISTINCT ';
            $this->_mapperObject->query .= $this->addFields();
            
            $this->_mapperObject->query .= $this->addOtherFields();
            
            $this->addTableName();
            
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
            
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if ($filter != 'brands') {
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
                }
            }
            
            $this->_mapperObject->query .= $this->getWhereNotEqual(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldOn']
            );
            
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableFieldWhere'],
                $this->categoriesArrayFilters[\Yii::$app->params['categoryKey']]['secondTableName']
            );
            
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableFieldWhere'],
                $this->categoriesArrayFilters[\Yii::$app->params['subCategoryKey']]['secondTableName']
            );
            
            foreach (\Yii::$app->params['filterKeys'] as $filter) {
                if ($filter != 'brands') {
                    $this->_mapperObject->query .= $this->getWhereIn(
                        $this->categoriesArrayFilters[$filter]['secondTableName'],
                        $this->categoriesArrayFilters[$filter]['secondTableFieldWhere'],
                        $filter . implode(",:{$filter}", (array_keys(ArrayHelper::getColumn($this->_mapperObject->model->$filter, \Yii::$app->params['idKey']))))
                    );
                    $this->addFilters($filter);
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    private function addFilters($filter)
    {
        $array = ArrayHelper::getColumn($this->_mapperObject->model->$filter, \Yii::$app->params['idKey']);
        foreach ($array as $key=>$val) {
            $this->_mapperObject->filtersArray[':' . $filter . $key] = $val;
        }
    }
}
