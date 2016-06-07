<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\UsersRulesModel;

/**
 * Создает объекты на оснований данных
 */
class UsersRulesAutonomicFactory extends AbstractGetObjectsFactory
{
    /**
     * @var array массив массивов данных для создания строк в БД
     */
    public $dataArray = array();
    /**
     * @var object объект на основании которого будут созданы объекты для каждой группы данных
     */
    public $model;
    /**
     * @var array результирующий массив объектов
     */
    private $_objectsArray = array();
    
    public function init()
    {
        parent::init();
        
        try {
            if (!isset($this->model)) {
                $this->model = new UsersRulesModel(['scenario'=>UsersRulesModel::GET_FROM_FORM]);
            }
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
            if (empty($this->dataArray)) {
                throw new ErrorException('Отсутствуют данные для построения объектов!');
            }
            
            foreach ($this->dataArray as $data) {
                if (!isset($this->model) || !is_object($this->model)) {
                    throw new ErrorException('Не задан объект класса модели для создания экземпляров!');
                }
                $model = clone $this->model;
                $model->attributes = $data;
                $this->_objectsArray[] = $model;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_objectsArray;
    }
}
