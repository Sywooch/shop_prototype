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
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы добавления продукта
    */
    const GET_FOR_ADD_PRODUCT = 'getForAddProduct';
    /**
     * Сценарий загрузки данных из формы добавления SizesModel в БД
    */
    const GET_FOR_ADD = 'getForAdd';
    /**
     * Сценарий загрузки данных из формы обновления SizesModel в БД
    */
    const GET_FOR_UPDATE = 'getForUpdate';
    /**
     * Сценарий загрузки данных из формы для удаления SizesModel из БД
    */
    const GET_FOR_DELETE = 'getForDelete';
    
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
            self::GET_FOR_ADD_PRODUCT=>['idArray'],
            self::GET_FOR_ADD=>['size'],
            self::GET_FOR_UPDATE=>['id', 'size'],
            self::GET_FOR_DELETE=>['id', 'size'],
        ];
    }
    
    public function rules()
    {
        return [
            [['idArray'], 'required', 'on'=>self::GET_FOR_ADD_PRODUCT],
            [['size'], 'required', 'on'=>self::GET_FOR_ADD],
            [['size'], 'app\validators\DecimalValidator', 'on'=>self::GET_FOR_ADD],
            [['size'], 'app\validators\SizesSizeExistsValidator', 'on'=>self::GET_FOR_ADD],
            [['id', 'size'], 'required', 'on'=>self::GET_FOR_UPDATE],
            [['size'], 'app\validators\DecimalValidator', 'on'=>self::GET_FOR_UPDATE],
            [['size'], 'app\validators\SizesSizeExistsValidator', 'on'=>self::GET_FOR_UPDATE, 'when'=>function($model) {
                return $model->size != MappersHelper::getSizesById($model)->size;
            }],
            [['id', 'size'], 'required', 'on'=>self::GET_FOR_DELETE],
            [['size'], 'app\validators\SizesForeignProductsExistsValidator', 'on'=>self::GET_FOR_DELETE],
        ];
    }
}
