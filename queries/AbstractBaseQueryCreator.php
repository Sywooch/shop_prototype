<?php

namespace app\queries;

use yii\base\{Object,
    ErrorException};
use app\mappers\AbstractBaseMapper;
use app\traits\{ExceptionsTrait,
    QueriesCreatorTrait};
use app\interfaces\VisitorInterface;

/**
 * Абстрактный суперкласс для подклассов, реализующих построение строки запроса к БД
 */
abstract class AbstractBaseQueryCreator extends Object implements VisitorInterface
{
    use ExceptionsTrait, QueriesCreatorTrait;
    
    /**
     * @var object объект на основании данных которого создается запрос,
     * запрос сохраняется в свойство $query этого объекта
     */
    protected $_mapperObject;
    
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве, реализуя VisitorInterface
     * запускает процесс
     * Метод addTableName() объявлен в трейте QueriesCreatorTrait
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
