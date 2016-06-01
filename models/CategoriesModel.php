<?php

namespace app\models;

use yii\base\Model;
use app\mappers\SubcategoryMapper;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;

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
    public $seocode;
    private $_subcategory = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'seocode'],
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
                if (!isset($this->id)) {
                    throw new ErrorException('Не определен id категории, для которой необходимо получить подкатегории!');
                }
                $subcategoryMapper = new SubcategoryMapper(['tableName'=>'subcategory', 'fields'=>['id', 'name', 'seocode'], 'model'=>$this]);
                $this->_subcategory = $subcategoryMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_subcategory;
    }
}
