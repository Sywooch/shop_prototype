<?php

namespace app\queries;

use app\queries\AbstractBaseQueryCreator;
use app\interfaces\VisitorInterface;
use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

class CategoriesQueryCreator extends AbstractBaseQueryCreator implements VisitorInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object объект на основании данных которого создается запрос,
     * запрос сохраняется в свойство $query этого объекта
     */
    private $_mapperObject;
    
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
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание запроса
     */
    public function getSelectQuery()
    {
        try {
            $this->addSelectHead();
            $this->addSelectEnd();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует начальную часть строки запроса к БД
     */
    private function addSelectHead()
    {
        try {
            $this->_mapperObject->query = 'SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->addTableName();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
