<?php

namespace app\mappers;

use yii\base\Component;
use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\interfaces\VisitorInterface;

/**
 * Абстрактный суперкласс, определяет интерфейс, общие свойства и методы для классов наследников, 
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
    public $tableName = NULL;
    /**
     * @var array столбцы данных, которые необходимо включить в запрос
     */
    public $fields = array();
    /**
     * @var array столбцы из JOIN таблиц, которые необходимо включить в выборку,
     * формат ['table'=>'tablename', 'fields'=>[['field'=>'fields1', 'as'=>'fields']]]
     */
    public $otherTablesFields = array();
    /**
     * @var string поле по которому будет произведена сортировка
     */
    public $orderByField = NULL;
    /**
     * @var string порядок сортировки ASC DESC
     */
    public $orderByType = NULL;
    /**
     * @var boolean определяет, нужна ли сортировка результатов выборки из БД в методе getData(),
     * по умолчанию true
     */
    public $getDataSorting = true;
    /**
     * @var int максимальное кол-во возвращаемых записей
     */
    public $limit = NULL;
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = NULL;
    /**
     * @var string имя класса, который создает объекты из данных
     */
    public $objectsClass = NULL;
    /**
     * @var string результирующая строка запроса
     */
    public $query = NULL;
    /**
     * @var array массив данных для подстановки в запрос
     */
    public $params = array();
    /**
     * @var object объект для получения данных, необходимых для построения объектов
     */
    public $model = NULL;
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
     * Передает классу-визитеру объект для дополнительной обработки данных
     * @param object объект класса-визитера
     * @return boolean
     */
    public function visit(VisitorInterface $visitor)
    {
        try {
            if (!$visitor->update($this)) {
                throw new ErrorException('Ошибка при вызове метода update класса-визитера!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует запрос к БД, выполняет его и формирует объекты, представляющих строки в БД
     * @return boolean
     */
    protected function run()
    {
        try {
            if (empty($this->queryClass)) {
                throw new ErrorException('Не задано имя класа, формирующего строку!');
            }
            if (!$this->visit(new $this->queryClass())) {
                throw new ErrorException('Ошибка при вызове конструктора запроса к БД!');
            }
            
            if (!$this->getData()) {
                throw new ErrorException('Ошибка при получении данных из БД!');
            }
            if (empty($this->DbArray)) {
                return false;
            }
            
            if (empty($this->objectsClass)) {
                throw new ErrorException('Не задано имя класа, который создает объекты из данных БД!');
            }
            if (!$this->visit(new $this->objectsClass())) {
                throw new ErrorException('Ошибка при вызове конструктора объектов!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
