<?php

namespace app\models;

use yii\base\Model;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\mappers\ColorsForProductMapper;
use app\mappers\SizesForProductMapper;
use app\mappers\SimilarProductsMapper;
use app\mappers\RelatedProductsMapper;

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
    private $_similar = NULL;
    private $_related = NULL;
    
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
                    'model'=>$this,
                ]);
                $this->_colors = $colorsMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_colors;
    }
    
    /**
     * Возвращает по запросу массив объектов sizes, связанных с текущим объектом
     * @return array
     */
    public function getSizes()
    {
        try {
            if (is_null($this->_sizes)) {
                if (!isset($this->id)) {
                    throw new ErrorException('Не определен id продукта, для которого необходимо получить размеры!');
                }
                $sizesMapper = new SizesForProductMapper([
                    'tableName'=>'sizes',
                    'fields'=>['id', 'size'],
                    'orderByField'=>'size',
                    'model'=>$this,
                ]);
                $this->_sizes = $sizesMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_sizes;
    }
    
    /**
     * Возвращает по запросу массив объектов similar products, связанных с текущим объектом
     * @return array
     */
    public function getSimilar()
    {
        try {
            if (is_null($this->_similar)) {
                if (!isset($this->id)) {
                    throw new ErrorException('Не определен id продукта, для которого необходимо получить похожие продукты!');
                }
                $similarProductsMapper = new SimilarProductsMapper([
                    'tableName'=>'products',
                    'fields'=>['id', 'name', 'price', 'images'],
                    'orderByField'=>'date',
                    'otherTablesFields'=>[
                        ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                        ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                    ],
                    'model'=>$this,
                ]);
                $this->_similar = $similarProductsMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_similar;
    }
    
    /**
     * Возвращает по запросу массив объектов related products, связанных с текущим объектом
     * @return array
     */
    public function getRelated()
    {
        try {
            if (is_null($this->_related)) {
                if (!isset($this->id)) {
                    throw new ErrorException('Не определен id продукта, для которого необходимо получить похожие продукты!');
                }
                $relatedProductsMapper = new RelatedProductsMapper([
                    'tableName'=>'products',
                    'fields'=>['id', 'name', 'price', 'images'],
                    'otherTablesFields'=>[
                        ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                        ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                    ],
                    'orderByField'=>'date',
                    'model'=>$this,
                ]);
                $this->_related = $relatedProductsMapper->getGroup();
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->_related;
    }
}
