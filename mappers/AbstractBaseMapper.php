<?php

namespace app\mappers;

use yii\base\Component;
use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use yii\base\Event;
use app\exceptions\LostDataUserException;

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
    public $tableName;
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
     * @var string имя класса, который создает объекты из данных
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
     * @var object объект, созданный из результирующих данных, полученных из БД, 
     * или объект, на основании которого будет создана запись в БД
     */
    public $objectsOne;
    
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
     */
    public function visit($visitor)
    {
        try {
            $visitor->update($this);
        } catch (LostDataUserException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует запрос к БД, выполняет его и формирует объекты, представляющих строки в БД
     */
    protected function run()
    {
        try {
            if (!isset($this->queryClass)) {
                throw new ErrorException('Не задано имя класа, формирующего строку!');
            }
            $this->visit(new $this->queryClass());
            
            $this->getData();
            
            if (!isset($this->objectsClass)) {
                throw new ErrorException('Не задано имя класа, который создает объекты из данных БД!');
            }
            $this->visit(new $this->objectsClass());
        } catch (LostDataUserException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
