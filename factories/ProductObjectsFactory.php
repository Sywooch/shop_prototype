<?php

namespace app\factories;

use app\factories\AbstractBaseFactory;
use app\models\ProductModel;
use app\interfaces\VisitorInterface;

class ProductObjectsFactory extends AbstractBaseFactory implements VisitorInterface
{
    /**
     * @var object объект на основании данных которого создаются объекты данных,
     * объекты данных сохраняются в свойство этого объекта
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
            $this->getObjects();
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductObjectsFactory::update\n" . $e->getMessage());
        }
    }
    
    /**
     * Создает на основе массива строк соответствующие объекты
     */
    public function getObjects()
    {
        try {
            foreach ($this->_mapperObject->DbArray as $entry) {
                $model = new ProductModel(['scenario'=>ProductModel::GET_FROM_DB]);
                $model->attributes = $entry;
                $this->_mapperObject->objectsArray[] = $model;
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при вызове метода ProductObjectsFactory::getObjects\n" . $e->getMessage());
        }
    }
}