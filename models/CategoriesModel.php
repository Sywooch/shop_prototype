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
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FOR_ADD = 'getForAdd';
    /**
     * Сценарий загрузки данных из формы обновления
    */
    const GET_FOR_UPDATE = 'getForUpdate';
    /**
     * Сценарий загрузки данных из формы удаления
    */
    const GET_FOR_DELETE = 'getForDelete';
    
    public $id;
    public $name;
    public $seocode;
    
    private $_subcategory = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name', 'seocode'],
            self::GET_FROM_FORM=>['name', 'seocode'],
            self::GET_FOR_ADD=>['name', 'seocode'],
            self::GET_FOR_UPDATE=>['id', 'name', 'seocode'],
            self::GET_FOR_DELETE=>['id', 'name', 'seocode'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'seocode'], 'required', 'on'=>self::GET_FOR_ADD],
            [['name'], 'app\validators\CategoriesNameExistsValidator', 'on'=>self::GET_FOR_ADD],
            [['seocode'], 'app\validators\CategoriesSeocodeExistsValidator', 'on'=>self::GET_FOR_ADD],
            [['id', 'name', 'seocode'], 'required', 'on'=>self::GET_FOR_UPDATE],
            [['name'], 'app\validators\CategoriesNameExistsValidator', 'on'=>self::GET_FOR_UPDATE, 'when'=>function($model) {
                return $model->name != MappersHelper::getCategoriesById(['model'=>$model])->name;
            }],
            [['seocode'], 'app\validators\CategoriesSeocodeExistsValidator', 'on'=>self::GET_FOR_UPDATE, 'when'=>function($model) {
                return $model->seocode != MappersHelper::getCategoriesById(['model'=>$model])->seocode;
            }],
            [['id', 'name', 'seocode'], 'required', 'on'=>self::GET_FOR_DELETE],
            [['name'], 'app\validators\CategoriesForeignSubcategoryExistsValidator', 'on'=>self::GET_FOR_DELETE],
            [['name'], 'app\validators\CategoriesForeignProductsExistsValidator', 'on'=>self::GET_FOR_DELETE],
            [['name', 'seocode'], 'app\validators\StripTagsValidator'],
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
