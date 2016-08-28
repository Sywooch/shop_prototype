<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;
use app\helpers\{MappersHelper,
    UploadHelper};

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД в рамках списка продуктов
    */
    const GET_FROM_DB = 'getFromDb';
    /**
     * Сценарий загрузки данных из формы
    */
    const GET_FROM_FORM = 'getFromForm';
    /**
     * Сценарий загрузки данных из формы, добавляющей продукт в корзину
    */
    const GET_FOR_CART = 'getForCart';
    /**
     * Сценарий загрузки данных из формы, удаляющей продукт из корзины
    */
    const GET_FOR_REMOVE = 'getForRemove';
    /**
     * Сценарий загрузки данных из формы, инициирующей очищающение корзины
    */
    const GET_FOR_CLEAR_CART = 'getForClearCart';
    /**
     * Сценарий загрузки данных из формы добавления продукта
    */
    const GET_FOR_ADD_PRODUCT = 'getForAddProduct';
    /**
     * Сценарий загрузки данных из формы для обновления товара
    */
    const GET_FOR_UPDATE = 'getForUpdate';
    /**
     * Сценарий cut загрузки данных из формы для обновления товара
    */
    const GET_FOR_UPDATE_CUT = 'getForUpdateCut';
    /**
     * Сценарий загрузки данных из формы для фильтра администрирования товаров
    */
    const GET_FOR_ADMIN_FILTER = 'getForAdminFilter';
    /**
     * Сценарий загрузки данных из формы для удаления товара из БД
    */
    const GET_FOR_DELETE = 'getForDelete'; 
    
    private $_id = null;
    private $_date = null;
    
    public $code;
    public $name;
    public $description;
    public $short_description;
    public $price;
    public $active;
    public $total_products = 0;
    
    /**
     * @var array массив объектов yii\web\UploadedFile
     */
    public $imagesToLoad;
    /**
     * @var string имя каталога, по которому в БД доступны изображения текущей модели
     */
    public $images = '';
    
    public $id_categories;
    public $id_subcategory;
    
    /**
     * @var string имена seocode категории и подкатегории продукта соответственно
     */
    private $_categories = null;
    private $_subcategory = null;
    /**
     * @var object объекты категории и подкатегории продукта соответственно
     */
    private $_categoriesObject = null; 
    private $_subcategoryObject = null; 
    
    /**
     * @var string хэш сумма для продукта, мспользуется для идентификации в массиве продуктов класса корзины
     */
    public $hash;
    /**
     * @var array массив свойств, на основании которых создается хэш
     */
    public $safeProperties = ['id', 'code', 'colorToCart', 'sizeToCart'];
    
    /**
     * Свойства получаемые из формы добавления в корзину
     */
    public $colorToCart;
    public $sizeToCart;
    public $quantity;
    
    private $_brands = null;
    private $_colors = null;
    private $_sizes = null;
    private $_similar = null;
    private $_related = null;
    private $_comments = null;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_FORM=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active', 'total_products'],
            self::GET_FROM_DB=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'categories', 'subcategory', 'id_categories', 'id_subcategory', 'active', 'total_products'],
            self::GET_FOR_CART=>['id', 'code', 'name', 'description', 'price', 'images', 'colorToCart', 'sizeToCart', 'quantity', 'categories', 'subcategory', 'hash'],
            self::GET_FOR_REMOVE=>['id', 'hash'],
            self::GET_FOR_CLEAR_CART=>['id', 'categories', 'subcategory'],
            self::GET_FOR_ADD_PRODUCT=>['code', 'name', 'description', 'short_description', 'price', 'imagesToLoad', 'id_categories', 'id_subcategory', 'active', 'total_products'],
            self::GET_FOR_UPDATE=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'imagesToLoad', 'id_categories', 'id_subcategory', 'active', 'total_products'],
            self::GET_FOR_UPDATE_CUT=>['id', 'active'],
            self::GET_FOR_ADMIN_FILTER=>['id_categories', 'id_subcategory', 'active'],
            self::GET_FOR_DELETE=>['id', 'images'], 
        ];
    }
    
    public function rules()
    {
        return [
            [['code', 'name', 'description', 'short_description', 'price', 'imagesToLoad', 'id_categories', 'id_subcategory', 'active'], 'required', 'on'=>self::GET_FOR_ADD_PRODUCT],
            [['imagesToLoad'], 'image', 'extensions'=>['png', 'jpg', 'gif'], 'mimeTypes'=>'image/*', 'maxSize'=>(1024*1024)*2, 'maxFiles'=>5, 'on'=>self::GET_FOR_ADD_PRODUCT],
            [['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'id_categories', 'id_subcategory'], 'required', 'on'=>self::GET_FOR_UPDATE], 
            [['imagesToLoad'], 'image', 'extensions'=>['png', 'jpg', 'gif'], 'mimeTypes'=>'image/*', 'maxSize'=>(1024*1024)*2, 'maxFiles'=>5, 'on'=>self::GET_FOR_UPDATE],
            [['id', 'active'], 'required', 'on'=>self::GET_FOR_UPDATE_CUT],
            [['id'], 'required', 'on'=>self::GET_FOR_DELETE],
            [['code', 'name', 'description', 'short_description'], 'app\validators\StripTagsValidator'],
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
     * Возвращает по запросу объект BrandsModel, связанный с текущим объектом
     * @return array
     */
    public function getBrands()
    {
        try {
            if (is_null($this->_brands)) {
                if (!empty($this->id)) {
                    $this->_brands = MappersHelper::getBrandsForProduct($this);
                }
            }
            return $this->_brands;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает по запросу массив объектов ColorsModel, связанных с текущим объектом
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
            if (!empty($this->_categories)) {
                $this->_categoriesObject = MappersHelper::getCategoriesBySeocode(new CategoriesModel(['seocode'=>$this->_categories]));
            }
            return (!empty($this->_categories) && !empty($this->_categoriesObject)) ? true : null;
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
                    $categoriesModel = MappersHelper::getCategoriesById(['model'=>new CategoriesModel(['id'=>$this->id_categories])]);
                    if (!is_object($categoriesModel) || !$categoriesModel instanceof CategoriesModel) {
                        return null;
                    }
                    $this->_categoriesObject = $categoriesModel;
                    $this->_categories = $categoriesModel->seocode;
                }
            }
            return $this->_categories;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_categoriesObject
     * @param CategoriesModel
     * @return boolean
     */
    public function setCategoriesObject(CategoriesModel $categoriesModel) 
    {
        try {
            $this->_categoriesObject = $categoriesModel;
            if (!empty($this->_categoriesObject)) {
                $this->_categories = $this->_categoriesObject->seocode;
            }
            return (!empty($this->_categories) && !empty($this->_categoriesObject)) ? true : null;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_categoriesObject
     * @return int
     */
    public function getCategoriesObject() 
    {
        try {
            if (is_null($this->_categoriesObject)) {
                if (!empty($this->id_categories)) {
                    $categoriesModel = MappersHelper::getCategoriesById(['model'=>new CategoriesModel(['id'=>$this->id_categories])]);
                    if (!is_object($categoriesModel) || !$categoriesModel instanceof CategoriesModel) {
                        return null;
                    }
                    $this->_categoriesObject = $categoriesModel;
                    $this->_categories = $categoriesModel->seocode;
                }
            }
            return $this->_categoriesObject;
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
            if (!empty($this->_subcategory)) {
                $this->_subcategoryObject = MappersHelper::getSubcategoryBySeocode(new SubcategoryModel(['seocode'=>$this->_subcategory]));
            }
            return (!empty($this->_subcategory) && !empty($this->_subcategoryObject)) ? true : null;
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
                    $this->_subcategoryObject = $subcategoryModel;
                    $this->_subcategory = $subcategoryModel->seocode;
                }
            }
            return $this->_subcategory;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $this->_subcategoryObject
     * @param SubcategoryModel
     * @return boolean
     */
    public function setSubcategoryObject(SubcategoryModel $subcategoryModel) 
    {
        try {
            $this->_subcategoryObject = $subcategoryModel;
            if (!empty($this->_subcategoryObject)) {
                $this->_subcategory = $this->_subcategoryObject->seocode;
            }
            return (!empty($this->_subcategory) && !empty($this->_subcategoryObject)) ? true : null;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает значение свойства $this->_subcategoryObject
     * @return int
     */
    public function getSubcategoryObject() 
    {
        try {
            if (is_null($this->_subcategoryObject)) {
                if (!empty($this->id_subcategory)) {
                    $subcategoryModel = MappersHelper::getSubcategoryById(new SubcategoryModel(['id'=>$this->id_subcategory]));
                    if (!is_object($subcategoryModel) || !$subcategoryModel instanceof SubcategoryModel) {
                        return null;
                    }
                    $this->_subcategoryObject = $subcategoryModel;
                    $this->_subcategory = $subcategoryModel->seocode;
                }
            }
            return $this->_subcategoryObject;
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
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                $date = \DateTime::createFromFormat('Y-m-d', $value);
                $value = $date->getTimestamp();
            }
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
    
    /**
     * Возвращает массив данных для сравнения
     * @return array
     */
    public function getDataArray()
    {
        try {
            return ['date'=>$this->date, 'code'=>$this->code, 'name'=>$this->name, 'short_description'=>$this->short_description, 'description'=>$this->description, 'price'=>$this->price, 'images'=>$this->images, 'id_categories'=>$this->id_categories, 'id_subcategory'=>$this->id_subcategory, 'active'=>$this->active, 'total_products'=>$this->total_products];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
