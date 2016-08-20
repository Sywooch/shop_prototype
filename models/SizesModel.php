<?php

namespace app\models;

use app\models\AbstractBaseModel;

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
        ];
    }
    
    public function rules()
    {
        return [
            [['idArray'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
            [['size'], 'required', 'on'=>self::GET_FROM_ADD_FORM],
            [['size'], 'app\validators\SizesSizeExistsValidator', 'on'=>self::GET_FROM_ADD_FORM],
        ];
    }
}
