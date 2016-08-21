<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы sizes
 */
class SizesModel extends AbstractBaseModel
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
     * Сценарий загрузки данных из формы добавления SizesModel в БД
    */
    const GET_FROM_ADD_FORM = 'getFromAddForm';
    /**
     * Сценарий загрузки данных из формы обновления SizesModel в БД
    */
    const GET_FROM_UPDATE_FORM = 'getFromUpdateForm';
    /**
     * Сценарий загрузки данных из формы для удаления SizesModel из БД
    */
    const GET_FROM_DELETE_FORM = 'getFromDeleteForm';
    
    public $id;
    public $size;
    /**
     * @var array массив id записей из БД
     */
    public $idArray = array();
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'size'],
            self::GET_FROM_ADD_PRODUCT_FORM=>['idArray'],
            self::GET_FROM_ADD_FORM=>['size'],
            self::GET_FROM_UPDATE_FORM=>['id', 'size'],
            self::GET_FROM_DELETE_FORM=>['id', 'size'],
        ];
    }
    
    public function rules()
    {
        return [
            [['idArray'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
            [['size'], 'required', 'on'=>self::GET_FROM_ADD_FORM],
            [['size'], 'app\validators\DecimalValidator', 'on'=>self::GET_FROM_ADD_FORM],
            [['size'], 'app\validators\SizesSizeExistsValidator', 'on'=>self::GET_FROM_ADD_FORM],
            [['id', 'size'], 'required', 'on'=>self::GET_FROM_UPDATE_FORM],
            [['size'], 'app\validators\DecimalValidator', 'on'=>self::GET_FROM_UPDATE_FORM],
            [['size'], 'app\validators\SizesSizeExistsValidator', 'on'=>self::GET_FROM_UPDATE_FORM, 'when'=>function($model) {
                return $model->size != MappersHelper::getSizesById($model)->size;
            }],
            [['id', 'size'], 'required', 'on'=>self::GET_FROM_DELETE_FORM],
            [['size'], 'app\validators\SizesForeignProductsExistsValidator', 'on'=>self::GET_FROM_DELETE_FORM],
        ];
    }
}
