<?php

namespace app\factories;

use app\factories\AbstractBaseFactory;
use yii\base\ErrorException;

/**
 * Конструирует массив объектов из массива строк БД
 */
abstract class AbstractGetOneFactory extends AbstractBaseFactory
{
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве,
     * запускает процесс
     * @param $object
     */
    public function update($object)
    {
        try {
            parent::update($object);
            $this->getOne();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает на основе массива данных объект
     */
    public function getOne()
    {
        try {
            if (empty($this->_mapperObject->DbArray)) {
                throw new ErrorException('Отсутствуют данные для построения объектов!');
            }
            
            if (!isset($this->model) || !is_object($this->model)) {
                throw new ErrorException('Не задан объект класса модели для создания экземпляров!');
            }
            $model = clone $this->model;
            $model->attributes = $this->_mapperObject->DbArray;
            $this->_mapperObject->objectsOne = $model;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
