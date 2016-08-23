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
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы добавления продукта
    */
    const GET_FOR_ADD_PRODUCT = 'getForAddProduct';
    /**
     * Сценарий загрузки данных из формы добавления ColorsModel в БД
    */
    const GET_FOR_ADD = 'getForAdd';
    /**
     * Сценарий загрузки данных из формы обновления ColorsModel в БД
    */
    const GET_FOR_UPDATE = 'getForUpdate';
    /**
     * Сценарий загрузки данных из формы для удаления ColorsModel из БД
    */
    const GET_FOR_DELETE = 'getForDelete';
    
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
            self::GET_FOR_ADD_PRODUCT=>['idArray'],
            self::GET_FOR_ADD=>['color'],
            self::GET_FOR_UPDATE=>['id', 'color'],
            self::GET_FOR_DELETE=>['id', 'color'],
        ];
    }
    
    public function rules()
    {
        return [
            [['idArray'], 'required', 'on'=>self::GET_FOR_ADD_PRODUCT],
            [['color'], 'required', 'on'=>self::GET_FOR_ADD],
            [['color'], 'app\validators\ColorsColorExistsValidator', 'on'=>self::GET_FOR_ADD],
            [['id', 'color'], 'required', 'on'=>self::GET_FOR_UPDATE],
            [['color'], 'app\validators\ColorsColorExistsValidator', 'on'=>self::GET_FOR_UPDATE, 'when'=>function($model) {
                return $model->color != MappersHelper::getColorsById($model)->color;
            }],
            [['id', 'color'], 'required', 'on'=>self::GET_FOR_DELETE],
            [['color'], 'app\validators\ColorsForeignProductsExistsValidator', 'on'=>self::GET_FOR_DELETE],
            [['color'], 'app\validators\StripTagsValidator'],
        ];
    }
}
