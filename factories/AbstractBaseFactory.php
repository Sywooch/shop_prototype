<?php

namespace app\factories;

use yii\base\Object;
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
    public $model = null;
    /**
     * @var object объект на основании данных которого создаются объекты данных,
     * объекты данных сохраняются в свойство objectsArray этого объекта
     */
    protected $_mapperObject = null;
    
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве,
     * запускает процесс
     * @param $object
     * @return boolean
     */
    public function update(AbstractBaseMapper $object)
    {
        try {
            $this->_mapperObject = $object;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
