<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\ProductsModel;
use yii\base\ErrorException;

/**
 * Создает объекты на оснований данных БД
 */
class ProductDetailObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new ProductsModel(['scenario'=>ProductsModel::GET_LIST_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает на основе массива данных строки объект
     */
    public function getObjects()
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
