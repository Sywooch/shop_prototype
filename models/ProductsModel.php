<?php

namespace app\models;

use yii\base\Model;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\mappers\ColorsForProductMapper;

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends Model
{
    use ExceptionsTrait;
    
    /**
     * Сценарий загрузки данных из БД в рамках списка продуктов
    */
    const GET_LIST_FROM_DB = 'getListFromBd';
    
    public $id;
    public $code;
    public $name;
    public $description;
    public $price;
    public $images;
    public $id_categories;
    public $id_subcategory;
    public $categories;
    public $subcategory;
    
    private $_colors = NULL;
    private $_sizes = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_LIST_FROM_DB=>['id', 'code', 'name', 'description', 'price', 'images', 'categories', 'subcategory'],
        ];
    }
    
    /**
     * Возвращает по запросу массив объектов colors, связанных с текущим объектом
     * @return array
     */
    public function getColors()
    {
        try {
            if (is_null($this->_colors)) {
                if (!isset($this->id)) {
                    throw new ErrorException('Не определен id продукта, для которого необходимо получить цвета!');
                }
                $colorsMapper = new ColorsForProductMapper([
                    'tableName'=>'colors',
                    'fields'=>['id', 'color'],
                    'orderByField'=>'color',
                    'productsModel'=>$this,
                ]);
                $this->_colors = $colorsMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_colors;
    }
}
