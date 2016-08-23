<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы currency
 */
class ColorsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    /**
     * Сценарий загрузки данных из формы добавления продукта
    */
    const GET_FROM_ADD_PRODUCT_FORM = 'getFromAddProductForm';
    /**
     * Сценарий загрузки данных из формы добавления ColorsModel в БД
    */
    const GET_FROM_ADD_FORM = 'getFromAddForm';
    /**
     * Сценарий загрузки данных из формы обновления ColorsModel в БД
    */
    const GET_FROM_UPDATE_FORM = 'getFromUpdateForm';
    /**
     * Сценарий загрузки данных из формы для удаления ColorsModel из БД
    */
    const GET_FROM_DELETE_FORM = 'getFromDeleteForm';
    
    public $id;
    public $color;
    /**
     * @var array массив id записей из БД
     */
    public $idArray = array();
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'color'],
            self::GET_FROM_ADD_PRODUCT_FORM=>['idArray'],
            self::GET_FROM_ADD_FORM=>['color'],
            self::GET_FROM_UPDATE_FORM=>['id', 'color'],
            self::GET_FROM_DELETE_FORM=>['id', 'color'],
        ];
    }
    
    public function rules()
    {
        return [
            [['idArray'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
            [['color'], 'required', 'on'=>self::GET_FROM_ADD_FORM],
            [['color'], 'app\validators\ColorsColorExistsValidator', 'on'=>self::GET_FROM_ADD_FORM],
            [['id', 'color'], 'required', 'on'=>self::GET_FROM_UPDATE_FORM],
            [['color'], 'app\validators\ColorsColorExistsValidator', 'on'=>self::GET_FROM_UPDATE_FORM, 'when'=>function($model) {
                return $model->color != MappersHelper::getColorsById($model)->color;
            }],
            [['id', 'color'], 'required', 'on'=>self::GET_FROM_DELETE_FORM],
            [['color'], 'app\validators\ColorsForeignProductsExistsValidator', 'on'=>self::GET_FROM_DELETE_FORM],
            [['color'], 'app\validators\StripTagsValidator'],
        ];
    }
}
