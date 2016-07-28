<?php

namespace app\models;

use app\models\AbstractBaseModel;
use yii\base\ErrorException;
use app\helpers\MappersHelper;
use app\helpers\UploadHelper;

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
    
    private $_id = null;
    private $_date = null;
    
    public $code = '';
    public $name = '';
    public $description = '';
    public $short_description = '';
    public $price = '';
    
    /**
     * @var array массив объектов yii\web\UploadedFile
     */
    public $imagesToLoad;
    /**
     * @var string имя каталога, по которому в БД доступны изображения текущей модели
     */
    public $images = '';
    
    public $id_categories = '';
    public $id_subcategory = '';
    
    /**
     * @var string имена seocode категории и подкатегории продукта соответственно
     */
    private $_categories = null;
    private $_subcategory = null;
    
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
    
    private $_colors = null;
    private $_sizes = null;
    private $_similar = null;
    private $_related = null;
    private $_comments = null;
    
    public function scenarios()
    {
        return [
            self::GET_LIST_FROM_DB=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'categories', 'subcategory', 'id_categories', 'id_subcategory'],
            self::GET_FROM_FORM_TO_CART=>['id', 'code', 'name', 'description', 'price', 'images', 'colorToCart', 'sizeToCart', 'quantity', 'categories', 'subcategory', 'hash'],
            self::GET_FROM_FORM_FOR_REMOVE=>['id', 'hash'],
            self::GET_FROM_FORM_FOR_CLEAR_CART=>['id', 'categories', 'subcategory'],
            self::GET_FROM_ADD_PRODUCT_FORM=>['code', 'name', 'description', 'short_description', 'price', 'imagesToLoad', 'id_categories', 'id_subcategory'],
        ];
    }
    
    public function rules()
    {
        return [
            [['code', 'name', 'description', 'short_description', 'price', 'imagesToLoad', 'id_categories', 'id_subcategory'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
            [['imagesToLoad'], 'image', 'extensions'=>['png', 'jpg', 'gif'], 'mimeTypes'=>'image/*', 'maxSize'=>(1024*1024)*2, 'maxFiles'=>5, 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
            [['code', 'name', 'description', 'short_description', 'price'], 'app\validators\StripTagsValidator', 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
        ];
    }
    
    /**
     * Присваивает значение свойству $this->_id
     * @param string/int $value значение ID
     * @return boolean
     */
    public function setId($value)
    {
        try {
            if (is_numeric($value)) {
                $this->_id = $value;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_id
     * @return int
     */
    public function getId()
    {
        try {
            if (is_null($this->_id)) {
                if (!empty($this->code)) {
                    $productsModel = MappersHelper::getProductsByCode($this);
                    if (!is_object($productsModel) || !$productsModel instanceof $this) {
                        return null;
                    }
                    $this->_id = $productsModel->id;
                }
            }
            return $this->_id;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
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
                    $this->_colors = MappersHelper::getColorsForProductList($this);
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
                    $this->_sizes = MappersHelper::getSizesForProductList($this);
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
                    $this->_similar = MappersHelper::getSimilarProductsList($this);
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
                    $this->_related = MappersHelper::getRelatedProductsList($this);
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
                    $this->_comments = MappersHelper::getCommentsForProductList($this);
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
    
    /**
     * Загружает группу изображений
     * @return boolean
     */
    public function upload()
    {
        try {
            if ($this->validate()) {
                if (!UploadHelper::saveImages($this->imagesToLoad)) {
                    return false;
                }
                if (!$this->images = UploadHelper::getСatalogName()) {
                    throw new ErrorException('Ошибка при получении имени каталога!');
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_categories
     * @param string $value
     * @return boolean
     */
    public function setCategories($value)
    {
        try {
            $this->_categories = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_categories
     * @return int
     */
    public function getCategories()
    {
        try {
            if (is_null($this->_categories)) {
                if (!empty($this->id_categories)) {
                    $categoriesModel = MappersHelper::getCategoriesById(new CategoriesModel(['id'=>$this->id_categories]));
                    if (!is_object($categoriesModel) || !$categoriesModel instanceof CategoriesModel) {
                        return null;
                    }
                    $this->_categories = $categoriesModel->seocode;
                }
            }
            return $this->_categories;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_subcategory
     * @param string $value
     * @return boolean
     */
    public function setSubcategory($value)
    {
        try {
            $this->_subcategory = $value;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_subcategory
     * @return int
     */
    public function getSubcategory()
    {
        try {
            if (is_null($this->_subcategory)) {
                if (!empty($this->id_subcategory)) {
                    $subcategoryModel = MappersHelper::getSubcategoryById(new SubcategoryModel(['id'=>$this->id_subcategory]));
                    if (!is_object($subcategoryModel) || !$subcategoryModel instanceof SubcategoryModel) {
                        return null;
                    }
                    $this->_subcategory = $subcategoryModel->seocode;
                }
            }
            return $this->_subcategory;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_date
     * @param string $value
     * @return boolean
     */
    public function setDate($value)
    {
        try {
            if (is_numeric($value)) {
                $this->_date = $value;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_date
     * @return int
     */
    public function getDate()
    {
        try {
            if (is_null($this->_date)) {
                $this->_date = time();
            }
            return $this->_date;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
