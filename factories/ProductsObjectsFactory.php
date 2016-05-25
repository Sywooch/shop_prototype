<?php

namespace app\factories;

use app\factories\AbstractBaseFactory;
use app\models\ProductsModel;
use app\interfaces\VisitorInterface;
use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Создает объекты на оснований данных БД
 */
class ProductsObjectsFactory extends AbstractBaseFactory implements VisitorInterface
{
    use ExceptionsTrait;
    
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
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает на основе массива строк массив объектов
     */
    public function getObjects()
    {
        try {
            if (empty($this->_mapperObject->DbArray)) {
                throw new ErrorException('Отсутствуют данные для построения объектов!');
            }
            
            foreach ($this->_mapperObject->DbArray as $entry) {
                $model = new ProductsModel(['scenario'=>ProductsModel::GET_LIST_FROM_DB]);
                $model->attributes = $entry;
                $this->_mapperObject->objectsArray[] = $model;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
