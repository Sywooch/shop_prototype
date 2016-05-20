<?php

namespace app\mappers;

use yii\base\Object;

/**
 * Абстрактный суперкласс, определяет интерфейс для классов наследников, 
 * получающих, создающих, обновляющих или удаляющих данные из БД
 */
abstract class BaseAbstractMapper extends Object
{
    /**
     * @var string имя таблицы, источника данных
     */
    public $tableName;
    /**
     * @var array столбцы данных, которые необходимо включить в выборку
     */
    public $fields = array();
    /**
     * @var string поле по которому будет произветена сортировка
     */
    public $orderByField;
    /**
     * @var string порядок сортировки ASC DESC
     */
    public $orderByRoute;
    
    /**
     * @var string результирующая строка запроса
     */
    protected $_query;
    
    abstract public function getGroup();
    /*abstract public function getOne($id);
    abstract public function add(Array $array);
    abstract public function update(Array $array);
    abstract public function delete(Array $array);*/
}
