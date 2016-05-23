<?php

namespace app\queries;

use app\queries\AbstractBaseQueryCreator;
use app\interfaces\VisitorInterface;
use yii\base\ErrorException;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductListQueryCreator extends AbstractBaseQueryCreator implements VisitorInterface
{
    /**
     * @var object объект на основании данных которого создается запрос,
     * запрос сохраняется в свойство $query этого объекта
     */
    private $_mapperObject;
    /**
     * @var array массив данных для выборки данных с учетом категории или(и) подкатегории, а также фильтров
     */
    public $categoriesArrayFilters = [
        'categories'=>[ # Данные для выборки из таблицы categories
            'firstTableName'=>'products', # Имя первой таблицы участвующей в объединении
            'firstTableFieldOn'=>'id_category', # Имя поля первой таблицы, по которому проходит объединение
            'secondTableName'=>'categories', # Имя второй таблицы участвующей в объединении
            'secondTableFieldOn'=>'id', # Имя поля второй таблицы, по которому проходит объединение
            'secondTableFieldWhere'=>'name', # Имя поля второй таблицы, по которому делается выборка с помощью WHERE
        ],
        'subcategory'=>[ # Данные для выборки из таблицы subcategory
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id_subcategory',
            'secondTableName'=>'subcategory',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'name',
        ],
        'products_colors'=>[ # Данные для выборки из таблицы products_colors
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_colors',
            'secondTableFieldOn'=>'id_product',
        ],
        'colors'=>[ # Данные для выборки из таблицы colors
            'firstTableName'=>'products_colors',
            'firstTableFieldOn'=>'id_color',
            'secondTableName'=>'colors',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'color',
        ],
        'products_sizes'=>[ # Данные для выборки из таблицы products_sizes
            'firstTableName'=>'products',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_sizes',
            'secondTableFieldOn'=>'id_product',
        ],
        'sizes'=>[ # Данные для выборки из таблицы sizes
            'firstTableName'=>'products_sizes',
            'firstTableFieldOn'=>'id_size',
            'secondTableName'=>'sizes',
            'secondTableFieldOn'=>'id',
            'secondTableFieldWhere'=>'size',
        ],
    ];
    
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве,
     * запускает процесс
     * @param $object
     */
    public function update($object)
    {
        try {
            $this->_mapperObject = $object;
            $this->getSelectQuery();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::update\n" . $e->getMessage());
        }
    }
    
    /**
     * Инициирует создание запроса, выбирая сценарий на основе данных из объекта Yii::$app->request
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
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::getSelectQuery\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует строку запроса к БД
     * @return string
     */
    private function queryForAll()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->addFilters();
            $this->addSelectEnd();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::queryForAll\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует строку запроса к БД, фильруя по категории
     * @return string
     */
    private function queryForCategory()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->getJoin(\Yii::$app->params['categoryKey']);
            $this->_mapperObject->query .= $this->addFilters();
            $this->_mapperObject->query .= $this->getWhere(\Yii::$app->params['categoryKey']);
            $this->addSelectEnd();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::queryForCategory\n" . $e->getMessage());
        }
        $this->_mapperObject->categoryFlag = true;
    }
    
    /**
     * Формирует строку запроса к БД, фильруя по подкатегории
     * @return string
     */
    private function queryForSubCategory()
    {
        try {
            $this->addSelectHead();
            $this->_mapperObject->query .= $this->getJoin(\Yii::$app->params['categoryKey']);
            $this->_mapperObject->query .= $this->getJoin(\Yii::$app->params['subCategoryKey']);
            $this->_mapperObject->query .= $this->addFilters();
            $this->_mapperObject->query .= $this->getWhere(\Yii::$app->params['categoryKey']);
            $this->_mapperObject->query .= $this->getWhere(\Yii::$app->params['subCategoryKey']);
            $this->addSelectEnd();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::queryForSubCategory\n" . $e->getMessage());
        }
        $this->_mapperObject->categoryFlag = true;
        $this->_mapperObject->subcategoryFlag = true;
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     */
    private function addSelectHead()
    {
        try {
            $this->_mapperObject->query = 'SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->_mapperObject->query .= $this->addTableName();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addSelectHead\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует часть запроса к БД, перечисляющую столбцы данных, которые необходимо включить в выборку
     * @return string
     */
    private function addFields()
    {
        try {
            $result = [];
            foreach ($this->_mapperObject->fields as $field) {
                $result[] = '[[' . $this->_mapperObject->tableName . '.' . $field . ']]';
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addFields\n" . $e->getMessage());
        }
        
        if (!empty($result)) {
            return implode(',', $result);
        }
        return '*';
    }
    
    /**
     * Формирует часть запроса к БД, указывающую из какой таблицы берутся данные
     * @return string
     */
    private function addTableName()
    {
        try {
            if (!isset($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addTableName\n" . $e->getMessage());
        }
        return ' FROM {{' . $this->_mapperObject->tableName . '}}';
    }
    
    /**
     * Формирует финальную часть строки запроса к БД
     */
    private function addSelectEnd()
    {
        try {
            $this->_mapperObject->query .= $this->addOrder();
            $this->_mapperObject->query .= $this->addLimit();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addSelectEnd\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую фильтры
     */
    private function addFilters()
    {
        try {
            $getArrayKeys = array_keys(\Yii::$app->request->get());
            
            foreach ($this->_mapperObject->filterKeys as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $this->_mapperObject->query .= $this->getJoin($this->_mapperObject->tableName . '_' . $filter);
                    $this->_mapperObject->query .= $this->getJoin($filter);
                    $this->_mapperObject->filtersArray[':' . $filter] = \Yii::$app->request->get($filter);
                }
            }
            foreach ($this->_mapperObject->filterKeys as $filter) {
                if (in_array($filter, $getArrayKeys)) {
                    $this->_mapperObject->query .= $this->getWhere($filter);
                }
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addFilters\n" . $e->getMessage());
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
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addOrder\n" . $e->getMessage());
        }
        return ' ORDER BY [[' . $this->_mapperObject->tableName . '.' . $this->_mapperObject->orderByField . ']] ' . $this->_mapperObject->orderByRoute;
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
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::addLimit\n" . $e->getMessage());
        }
        return ' LIMIT 0, ' . $this->_mapperObject->limit;
    }
    
    /**
     * Формирует часть запроса к БД, объединяющую таблицы
     * @return string
    */
    private function getJoin($key)
    {
        try {
            return ' JOIN {{' . $this->categoriesArrayFilters[$key]['secondTableName'] . '}} ON [[' . $this->categoriesArrayFilters[$key]['firstTableName'] . '.' . $this->categoriesArrayFilters[$key]['firstTableFieldOn'] . ']]=[[' . $this->categoriesArrayFilters[$key]['secondTableName'] . '.' . $this->categoriesArrayFilters[$key]['secondTableFieldOn'] . ']]';
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::getJoins\n" . $e->getMessage());
        }
    }
    
    /**
     * Формирует часть запроса к БД, добавляющую условия выборки
     * @return string
    */
    private function getWhere($key)
    {
        try {
            $string = strpos($this->_mapperObject->query, 'WHERE') ? ' AND' : ' WHERE';
            return $string . ' [[' . $this->categoriesArrayFilters[$key]['secondTableName'] . '.' . $this->categoriesArrayFilters[$key]['secondTableFieldWhere'] . ']]=:' . $key;
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductListQueryCreator::getWhere\n" . $e->getMessage());
        }
    }
}
