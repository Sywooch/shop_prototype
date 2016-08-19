<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы categories
 */
class CategoriesModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_ADD_FORM = 'getFromAddForm';
    /**
     * Сценарий загрузки данных из формы обновления
    */
    const GET_FROM_UPDATE_FORM = 'getFromUpdateForm';
    /**
     * Сценарий загрузки данных из формы удаления
    */
    const GET_FROM_DELETE_FORM = 'getFromDeleteForm';
    
    public $id;
    public $name;
    public $seocode;
    
    private $_subcategory = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'seocode'],
            self::GET_FROM_ADD_FORM=>['name', 'seocode'],
            self::GET_FROM_UPDATE_FORM=>['id', 'name', 'seocode'],
            self::GET_FROM_DELETE_FORM=>['id', 'name', 'seocode'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'seocode'], 'required', 'on'=>self::GET_FROM_ADD_FORM],
            [['name'], 'app\validators\CategoriesNameExistsValidator', 'on'=>self::GET_FROM_ADD_FORM],
            [['seocode'], 'app\validators\CategoriesSeocodeExistsValidator', 'on'=>self::GET_FROM_ADD_FORM],
            [['id', 'name', 'seocode'], 'required', 'on'=>self::GET_FROM_UPDATE_FORM],
            [['name'], 'app\validators\CategoriesNameExistsValidator', 'on'=>self::GET_FROM_UPDATE_FORM, 'when'=>function($model) {
                return $model->name != MappersHelper::getCategoriesById($model)->name;
            }],
            [['seocode'], 'app\validators\CategoriesSeocodeExistsValidator', 'on'=>self::GET_FROM_UPDATE_FORM, 'when'=>function($model) {
                return $model->seocode != MappersHelper::getCategoriesById($model)->seocode;
            }],
            [['id', 'name', 'seocode'], 'required', 'on'=>self::GET_FROM_DELETE_FORM],
            [['name'], 'app\validators\CategoriesForeignSubcategoryExistsValidator', 'on'=>self::GET_FROM_DELETE_FORM],
            [['name'], 'app\validators\CategoriesForeignProductsExistsValidator', 'on'=>self::GET_FROM_DELETE_FORM],
            [['name', 'seocode'], 'trim'],
            [['seocode'], 'app\validators\StrtolowerValidator'],
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
                if (!empty($this->id)) {
                    $this->_subcategory = MappersHelper::getSubcategoryForCategoryList($this);
                }
            }
            return $this->_subcategory;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
