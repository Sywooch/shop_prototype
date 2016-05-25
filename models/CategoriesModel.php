<?php

namespace app\models;

use yii\base\Model;
use app\mappers\SubcategoryMapper;
use app\traits\ExceptionsTrait;

/**
 * Представляет данные таблицы categories
 */
class CategoriesModel extends Model
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $name;
    private $_subcategory = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name'],
        ];
    }
    
    /**
     * Возвращает по запросу массив объектов подкатегорий, связанных с категорией, представленной текущим объектом
     * @return array
     */
    public function getSubcategory()
    {
        try {
            if (is_null($this->_subcategory)) {
                $subcategoryMapper = new SubcategoryMapper(['tableName'=>'subcategory', 'fields'=>['id', 'name'], 'categoriesModel'=>$this]);
                $this->_subcategory = $subcategoryMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_subcategory;
    }
}
