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
    /**
     * Сценарий загрузки данных из формы добавления продукта
    */
    const GET_FROM_ADD_PRODUCT_FORM = 'getFromAddProductForm';
    
    public $id = '';
    public $date = '';
    public $code = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $images = array();
    public $id_categories = '';
    public $id_subcategory = '';
    
    /**
     * Свойства получаемые при выборке связанных и похожих продуктов, например, для построения ссылок
     */
    public $categories = '';
    public $subcategory = '';
    
    /**
     * @var string хэш сумма для продукта, мспользуется для идентификации в массиве продуктов класса корзины
     */
    public $hash = '';
    /**
     * @var array массив свойств, на основании которых создается хэш
     */
    public $safeProperties = ['id', 'code', 'colorToCart', 'sizeToCart'];
    
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
            self::GET_FROM_FORM_TO_CART=>['id', 'code', 'name', 'description', 'price', 'colorToCart', 'sizeToCart', 'quantity', 'categories', 'subcategory', 'hash'],
            self::GET_FROM_FORM_FOR_REMOVE=>['id', 'hash'],
            self::GET_FROM_FORM_FOR_CLEAR_CART=>['id', 'categories', 'subcategory'],
            self::GET_FROM_ADD_PRODUCT_FORM=>['code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'],
        ];
    }
    
    public function rules()
    {
        return [
            [['code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
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
                if (!empty($this->id)) {
                    $colorsMapper = new ColorsForProductMapper([
                        'tableName'=>'colors',
                        'fields'=>['id', 'color'],
                        'orderByField'=>'color',
                        'model'=>$this,
                    ]);
                    $colorsArray = $colorsMapper->getGroup();
                    if (!is_array($colorsArray) || empty($colorsArray)) {
                        return NULL;
                    }
                    $this->_colors = $colorsArray;
                }
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
                if (!empty($this->id)) {
                    $sizesMapper = new SizesForProductMapper([
                        'tableName'=>'sizes',
                        'fields'=>['id', 'size'],
                        'orderByField'=>'size',
                        'model'=>$this,
                    ]);
                    $sizesArray = $sizesMapper->getGroup();
                    if (!is_array($sizesArray) || empty($sizesArray)) {
                        return NULL;
                    }
                    $this->_sizes = $sizesArray;
                }
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
                if (!empty($this->id)) {
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
                        return NULL;
                    }
                    $this->_similar = $similarsArray;
                }
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
                if (!empty($this->id)) {
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
                        return NULL;
                    }
                    $this->_related = $relatedArray;
                }
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
                if (!empty($this->id)) {
                    $commentsForProductMapper = new CommentsForProductMapper([
                        'tableName'=>'comments',
                        'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
                        'model'=>$this,
                    ]);
                    $commentsArray = $commentsForProductMapper->getGroup();
                    if (!is_array($commentsArray) || empty($commentsArray)) {
                        return NULL;
                    }
                    $this->_comments = $commentsArray;
                }
            }
            return $this->_comments;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает md5 hash из значений свойств, однозначно идентифицируя объект
     * @return boolean
     */
    public function getHash()
    {
        try {
            $stringToHash = '';
            foreach ($this->safeProperties as $property) {
                $stringToHash .= $this->$property;
            }
            if (!$this->hash = md5($stringToHash)) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
