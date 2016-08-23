<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы currency
 */
class BrandsModel extends AbstractBaseModel
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
     * Сценарий загрузки данных из формы добавления BrandsModel в БД
    */
    const GET_FROM_ADD_FORM = 'getFromAddForm';
    /**
     * Сценарий загрузки данных из формы обновления BrandsModel в БД
    */
    const GET_FROM_UPDATE_FORM = 'getFromUpdateForm';
    /**
     * Сценарий загрузки данных из формы для удаления BrandsModel из БД
    */
    const GET_FROM_DELETE_FORM = 'getFromDeleteForm';
    
    public $id;
    public $brand;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'brand'],
            self::GET_FROM_ADD_PRODUCT_FORM=>['id'],
            self::GET_FROM_ADD_FORM=>['brand'],
            self::GET_FROM_UPDATE_FORM=>['id', 'brand'],
            self::GET_FROM_DELETE_FORM=>['id', 'brand'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
            [['brand'], 'required', 'on'=>self::GET_FROM_ADD_FORM],
            [['brand'], 'app\validators\BrandsBrandExistsValidator', 'on'=>self::GET_FROM_ADD_FORM],
            [['id', 'brand'], 'required', 'on'=>self::GET_FROM_UPDATE_FORM],
            [['brand'], 'app\validators\BrandsBrandExistsValidator', 'on'=>self::GET_FROM_UPDATE_FORM, 'when'=>function($model) {
                return $model->brand != MappersHelper::getBrandsById($model)->brand;
            }],
            [['id', 'brand'], 'required', 'on'=>self::GET_FROM_DELETE_FORM],
            [['brand'], 'app\validators\BrandsForeignProductsExistsValidator', 'on'=>self::GET_FROM_DELETE_FORM],
            [['brand'], 'app\validators\StripTagsValidator'],
        ];
    }
}
