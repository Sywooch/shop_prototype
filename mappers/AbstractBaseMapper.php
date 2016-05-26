<?php

namespace app\mappers;

use yii\base\Object;
use app\traits\ExceptionsTrait;

/**
 * Абстрактный суперкласс, определяет интерфейс для классов наследников, 
 * получающих, создающих, обновляющих или удаляющих данные из БД
 */
abstract class AbstractBaseMapper extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var string имя таблицы, источника данных
     */
    public $tableName;
    /**
     * @var array столбцы данных, которые необходимо включить запрос
     */
    public $fields = array();
    /**
     * @var string поле по которому будет произведена сортировка
     */
    public $orderByField;
    /**
     * @var string порядок сортировки ASC DESC
     */
    public $orderByRoute;
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass;
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass;
    /**
     * @var string результирующая строка запроса
     */
    public $query;
    /**
     * @var array массив результирующих данных, полученный из БД
     */
    public $DbArray = array();
    /**
     * @var array массив объектов, созданных из результирующих данных, полученных из БД
     */
    public $objectsArray = array();
    
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    abstract public function getGroup();
}
