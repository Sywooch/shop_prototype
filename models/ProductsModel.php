<?php

namespace app\models;

use app\models\AbstractBaseModel;
use yii\base\ErrorException;
use app\mappers\ColorsForProductMapper;
use app\mappers\SizesForProductMapper;
use app\mappers\SimilarProductsMapper;
use app\mappers\RelatedProductsMapper;
use app\mappers\CommentsForProductMapper;

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД в рамках списка продуктов
    */
    const GET_LIST_FROM_DB = 'getListFromBd';
    /**
     * Сценарий загрузки данных из формы, добавляющей продукт в корзину
    */
    const GET_FROM_FORM_TO_CART = 'getFromFormToCart';
    /**
     * Сценарий загрузки данных из формы, удаляющей продукт из корзины
    */
    const GET_FROM_FORM_FOR_REMOVE = 'getFromFormForRemove';
    /**
     * Сценарий загрузки данных из формы, инициирующей очищающение корзины
    */
    const GET_FROM_FORM_FOR_CLEAR_CART = 'getFromFormForClearCart';
    
    public $id = '';
    public $date = '';
    public $code = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $images = '';
    public $id_categories = '';
    public $id_subcategory = '';
    
    /**
     * Свойства получаемые при выборке связанных и похожих продуктов, например, для построения ссылок
     */
    public $categories = '';
    public $subcategory = '';
    
    /**
     * Свойства получаемые из формы добавления в корзину
     */
    public $colorToCart = '';
    public $sizeToCart = '';
    public $quantity = '';
    
    private $_colors = NULL;
    private $_sizes = NULL;
    private $_similar = NULL;
    private $_related = NULL;
    private $_comments = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_LIST_FROM_DB=>['id', 'date', 'code', 'name', 'description', 'price', 'images', 'categories', 'subcategory'],
            self::GET_FROM_FORM_TO_CART=>['id', 'code', 'name', 'description', 'price', 'colorToCart', 'sizeToCart', 'quantity', 'categories', 'subcategory'],
            self::GET_FROM_FORM_FOR_REMOVE=>['id'],
            self::GET_FROM_FORM_FOR_CLEAR_CART=>['id', 'categories', 'subcategory'],
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
                if (empty($this->id)) {
                    throw new ErrorException('Не определен id продукта, для которого необходимо получить цвета!');
                }
                $colorsMapper = new ColorsForProductMapper([
                    'tableName'=>'colors',
                    'fields'=>['id', 'color'],
                    'orderByField'=>'color',
                    'model'=>$this,
                ]);
                $colorsArray = $colorsMapper->getGroup();
                if (!is_array($colorsArray) || empty($colorsArray)) {
                    return false;
                }
                $this->_colors = $colorsArray;
            }
            return $this->_colors;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает по запросу массив объектов sizes, связанных с текущим объектом
     * @return array
     */
    public function getSizes()
    {
        try {
            if (is_null($this->_sizes)) {
                if (empty($this->id)) {
                    throw new ErrorException('Не определен id продукта, для которого необходимо получить размеры!');
                }
                $sizesMapper = new SizesForProductMapper([
                    'tableName'=>'sizes',
                    'fields'=>['id', 'size'],
                    'orderByField'=>'size',
                    'model'=>$this,
                ]);
                $sizesArray = $sizesMapper->getGroup();
                if (!is_array($sizesArray) || empty($sizesArray)) {
                    return false;
                }
                $this->_sizes = $sizesArray;
            }
            return $this->_sizes;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает по запросу массив объектов similar products, связанных с текущим объектом
     * @return array
     */
    public function getSimilar()
    {
        try {
            if (is_null($this->_similar)) {
                if (empty($this->id)) {
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
                $similarsArray = $similarProductsMapper->getGroup();
                if (!is_array($similarsArray) || empty($similarsArray)) {
                    return false;
                }
                $this->_similar = $similarsArray;
            }
            return $this->_similar;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает по запросу массив объектов related products, связанных с текущим объектом
     * @return array
     */
    public function getRelated()
    {
        try {
            if (is_null($this->_related)) {
                if (empty($this->id)) {
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
                $relatedArray = $relatedProductsMapper->getGroup();
                if (!is_array($relatedArray) || empty($relatedArray)) {
                    return false;
                }
                $this->_related = $relatedArray;
            }
            return $this->_related;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        
    }
    
    /**
     * Возвращает по запросу массив объектов comments, связанных с текущим объектом
     * @return array
     */
    public function getComments()
    {
        try {
            if (is_null($this->_comments)) {
                if (empty($this->id)) {
                    throw new ErrorException('Не определен id продукта, для которого необходимо получить комментарии!');
                }
                $commentsForProductMapper = new CommentsForProductMapper([
                    'tableName'=>'comments',
                    'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
                    'model'=>$this,
                ]);
                $commentsArray = $commentsForProductMapper->getGroup();
                if (!is_array($commentsArray) || empty($commentsArray)) {
                    return false;
                }
                $this->_comments = $commentsArray;
            }
            return $this->_comments;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
