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
    const GET_FOR_ADD_PRODUCT = 'getForAddProduct';
    /**
     * Сценарий загрузки данных из формы добавления BrandsModel в БД
    */
    const GET_FOR_ADD = 'getForAdd';
    /**
     * Сценарий загрузки данных из формы обновления BrandsModel в БД
    */
    const GET_FOR_UPDATE = 'getForUpdate';
    /**
     * Сценарий загрузки данных из формы для удаления BrandsModel из БД
    */
    const GET_FOR_DELETE = 'getForDelete';
    
    public $id;
    public $brand;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'brand'],
            self::GET_FOR_ADD_PRODUCT=>['id'],
            self::GET_FOR_ADD=>['brand'],
            self::GET_FOR_UPDATE=>['id', 'brand'],
            self::GET_FOR_DELETE=>['id', 'brand'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::GET_FOR_ADD_PRODUCT],
            [['brand'], 'required', 'on'=>self::GET_FOR_ADD],
            [['brand'], 'app\validators\BrandsBrandExistsValidator', 'on'=>self::GET_FOR_ADD],
            [['id', 'brand'], 'required', 'on'=>self::GET_FOR_UPDATE],
            [['brand'], 'app\validators\BrandsBrandExistsValidator', 'on'=>self::GET_FOR_UPDATE, 'when'=>function($model) {
                return $model->brand != MappersHelper::getBrandsById($model)->brand;
            }],
            [['id', 'brand'], 'required', 'on'=>self::GET_FOR_DELETE],
            [['brand'], 'app\validators\BrandsForeignProductsExistsValidator', 'on'=>self::GET_FOR_DELETE],
            [['brand'], 'app\validators\StripTagsValidator'],
        ];
    }
}
