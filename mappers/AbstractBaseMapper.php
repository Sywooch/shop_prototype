<?php

namespace app\mappers;

use yii\base\Component;
use app\traits\ExceptionsTrait;
use yii\base\Event;

/**
 * Абстрактный суперкласс, определяет интерфейс для классов наследников, 
 * получающих, создающих, обновляющих или удаляющих данные из БД
 */
abstract class AbstractBaseMapper extends Component
{
    use ExceptionsTrait;
    
    /**
     * Константа события выполнения запроса к БД
     */
    const SENT_REQUESTS_TO_DB = 'sentRequestsToDb';
    
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
    public $orderByType;
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
    
    public function init()
    {
        parent::init();
        
        if (YII_DEBUG) {
            $this->on($this::SENT_REQUESTS_TO_DB, ['app\helpers\FixSentRequests', 'fix']); # Регистрирует обработчик, подсчитывающий обращения к БД
        }
    }
    
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    abstract public function getGroup();
}
