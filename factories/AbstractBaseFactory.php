<?php

namespace app\factories;

use yii\base\Object;
use yii\base\ErrorException;
use app\mappers\AbstractBaseMapper;
use app\traits\ExceptionsTrait;
use app\interfaces\VisitorInterface;

/**
 * Определяет интерфейс, общие свойства и методы классов-наследников, конструирующих объекты
 */
abstract class AbstractBaseFactory extends Object implements VisitorInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object объект класса модели для создания экземпляров
     */
    public $model;
    /**
     * @var object объект на основании данных которого создаются объекты данных,
     * объекты данных сохраняются в свойство objectsArray этого объекта
     */
    protected $_mapperObject;
    
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве,
     * запускает процесс
     * @param $object
     * @return boolean
     */
    public function update($object)
    {
        try {
            if (!is_object($object)) {
                throw new ErrorException('Переденный аргумент не является объектом!');
            }
            if (!$object instanceof AbstractBaseMapper) {
                throw new ErrorException('Переденный аргумент должен принадлежать к типу AbstractBaseMapper!');
            }
            $this->_mapperObject = $object;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
