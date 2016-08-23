<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы subcategory
 */
class SubcategoryModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
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
    public $id_categories;
    
    /**
     * @var object объект CategoriesModel, с которым связана подкатегория
     */
    private $_categories = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'seocode', 'id_categories'],
            self::GET_FROM_FORM=>['name', 'seocode', 'id_categories'],
            self::GET_FROM_ADD_FORM=>['name', 'seocode', 'id_categories'],
            self::GET_FROM_UPDATE_FORM=>['id', 'name', 'seocode', 'id_categories'],
            self::GET_FROM_DELETE_FORM=>['id', 'name', 'seocode', 'id_categories'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'seocode', 'id_categories'], 'required', 'on'=>self::GET_FROM_ADD_FORM],
            [['name'], 'app\validators\SubcategoryNameExistsValidator', 'on'=>self::GET_FROM_ADD_FORM],
            [['seocode'], 'app\validators\SubcategorySeocodeExistsValidator', 'on'=>self::GET_FROM_ADD_FORM],
            [['id', 'name', 'seocode', 'id_categories'], 'required', 'on'=>self::GET_FROM_UPDATE_FORM],
            [['name'], 'app\validators\SubcategoryNameExistsValidator', 'on'=>self::GET_FROM_UPDATE_FORM, 'when'=>function($model) {
                return $model->name != MappersHelper::getSubcategoryById($model)->name;
            }],
            [['seocode'], 'app\validators\SubcategorySeocodeExistsValidator', 'on'=>self::GET_FROM_UPDATE_FORM, 'when'=>function($model) {
                return $model->seocode != MappersHelper::getSubcategoryById($model)->seocode;
            }],
            [['id', 'name', 'seocode', 'id_categories'], 'required', 'on'=>self::GET_FROM_DELETE_FORM],
            [['name'], 'app\validators\SubcategoryForeignProductsExistsValidator', 'on'=>self::GET_FROM_DELETE_FORM],
            [['name', 'seocode'], 'app\validators\StripTagsValidator'],
            [['seocode'], 'app\validators\StrtolowerValidator'],
        ];
    }
    
    /**
     * Возвращает объект CategoriesModel, с которым связана подкатегория
     * @return object CategoriesModel
     */
    public function getCategories()
    {
        try {
            if (is_null($this->_categories)) {
                if (!empty($this->id_categories)) {
                    $this->_categories = MappersHelper::getCategoriesById(new CategoriesModel(['id'=>$this->id_categories]));
                }
            }
            return $this->_categories;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
}
