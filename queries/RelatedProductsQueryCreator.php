<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class RelatedProductsQueryCreator extends AbstractSeletcQueryCreator
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
        'id'=>[ # Данные для выборки из таблицы colors
            'firstTableName'=>'products', # Имя первой таблицы участвующей в объединении
            'firstTableFieldOn'=>'id', # Имя поля первой таблицы, по которому проходит объединение
            'secondTableName'=>'related_products', # Имя второй таблицы участвующей в объединении
            'secondTableFieldOn'=>'id_related_products', # Имя поля второй таблицы, по которому проходит объединение
            'unionSecondTableFieldOn'=>'id_products', # Имя поля второй таблицы, по которому проходит объединение
            'secondTableFieldWhere'=>'id_products', # Имя поля второй таблицы, по которому делается выборка с помощью WHERE
            'unionSecondTableFieldWhere'=>'id_related_products', # Имя поля второй таблицы, по которому делается выборка с помощью WHERE
        ],
    ];
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            $this->_mapperObject->query .= 'SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->_mapperObject->query .= $this->addOtherFields();
            $this->_mapperObject->query .= $this->addTableName();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldOn']
            );
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
            $this->_mapperObject->query .= $this->getWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
            $this->_mapperObject->query .= ' UNION SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->_mapperObject->query .= $this->addOtherFields();
            $this->_mapperObject->query .= $this->addTableName();
            $this->_mapperObject->query .= $this->getJoin(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['firstTableFieldOn'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['unionSecondTableFieldOn']
            );
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
            $this->_mapperObject->query .= $this->getWhereWhere(
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['secondTableName'],
                $this->categoriesArrayFilters[\Yii::$app->params['idKey']]['unionSecondTableFieldWhere'],
                \Yii::$app->params['idKey']
            );
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
