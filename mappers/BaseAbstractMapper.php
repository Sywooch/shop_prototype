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
     * @var array столбцы данных, которые необходимо включить запрос
     */
    public $fields = array();
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
     * Передает классу-визитеру объект для дополнительной обработки данных
     * @param object объект класса-визитера
     */
    public function visit($visitor)
    {
        $visitor->update($this);
    }
    
    /**
     * Возвращает массив объектов, представляющий строки в БД
     * @return array
     */
    abstract public function getGroup();
}
