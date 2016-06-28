<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\mappers\SubcategoryMapper;
use yii\base\ErrorException;

/**
 * Представляет данные таблицы categories
 */
class CategoriesModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id = '';
    public $name = '';
    public $seocode = '';
    
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
                if (empty($this->id)) {
                    throw new ErrorException('Не определен id категории, для которой необходимо получить подкатегории!');
                }
                $subcategoryMapper = new SubcategoryMapper([
                    'tableName'=>'subcategory',
                    'fields'=>['id', 'name', 'seocode'],
                    'model'=>$this
                ]);
                $subcategoryArray = $subcategoryMapper->getGroup();
                if (!is_array($subcategoryArray) || empty($subcategoryArray)) {
                    return false;
                }
                $this->_subcategory = $subcategoryArray;
            }
            return $this->_subcategory;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
