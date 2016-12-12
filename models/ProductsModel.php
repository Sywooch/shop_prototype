<?php

namespace app\models;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\models\{AbstractBaseModel,
    CategoriesModel,
    ProductsFilter,
    SizesModel,
    SubcategoryModel};
use app\helpers\PicturesHelper;
use app\exceptions\ExceptionsTrait;
use app\repositories\{GetGroupRepositoryInterface,
    SimilarProductsRepository};
use app\finders\{CategoryFinder,
    ColorsProductFinder,
    SizesProductFinder,
    SubcategoryFinder};
use app\collections\{BaseCollection,
    CollectionInterface};

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * Сценарий сохранения данных из формы добавления товара
    */
    const GET_FROM_ADD_PRODUCT = 'getFromAddProduct';
    /**
     * Сценарий сохранения данных из формы добавления товара в корзину
    */
    const GET_FROM_ADD_TO_CART = 'getFromAddToCart';
    
    private $colorsFinder;
    private $sizesFinder;
    private $categoryFinder;
    private $subcategoryFinder;
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'products';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_ADD_PRODUCT=>['date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'active', 'total_products', 'seocode'],
            self::GET_FROM_ADD_TO_CART=>['price'],
        ];
    }
    
    public function rules()
    {
        return [
            [['date', 'code', 'name', 'description', 'short_description', 'total_products', 'seocode'], 'app\validators\StripTagsValidator'],
            [['code', 'name', 'description', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'total_products'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['code'], 'app\validators\ProductCodeValidator', 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['seocode'], 'app\validators\ProductSeocodeValidator', 'skipOnEmpty'=>false, 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['price'], 'app\validators\ProductPriceValidator', 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['images'], 'image', 'extensions'=>['jpeg', 'jpg', 'gif', 'png'], 'mimeTypes'=>'image/*', 'maxSize'=>1024*1024, 'maxFiles'=>5, 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['date'], 'app\validators\ProductDateValidator', 'skipOnEmpty'=>false, 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['total_products'], 'app\validators\ProductTotalProductsValidator', 'on'=>self::GET_FROM_ADD_PRODUCT],
            [['price'], 'required', 'on'=>self::GET_FROM_ADD_TO_CART],
        ];
    }
    
    /**
     * Получает объект CategoriesModel, с которой связан текущий объект ProductsModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getCategory(): CategoriesModel
    {
        try {
            if (empty($this->categoryFinder)) {
                $this->categoryFinder = new CategoryFinder(['collection'=>new BaseCollection()]);
            }
            $this->categoryFinder->load(['id'=>$this->id_category]);
            $model = $this->categoryFinder->find()->getModel();
            if (empty($model)) {
                throw new ErrorException($this->emptyError('category'));
            }
            
            return $model;
            //return $this->hasOne(CategoriesModel::class, ['id'=>'id_category'])->inverseOf('products');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SubcategoryModel, с которой связан текущий объект ProductsModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getSubcategory(): SubcategoryModel
    {
        try {
            if (empty($this->subcategoryFinder)) {
                $this->subcategoryFinder = new SubcategoryFinder(['collection'=>new BaseCollection()]);
            }
            $this->subcategoryFinder->load(['id'=>$this->id_subcategory]);
            $model = $this->subcategoryFinder->find()->getModel();
            if (empty($model)) {
                throw new ErrorException($this->emptyError('subcategory'));
            }
            
            return $model;
            //return $this->hasOne(SubcategoryModel::class, ['id'=>'id_subcategory'])->inverseOf('products');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив ColorsModel, с которыми связан текущий объект ProductsModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getColors(): CollectionInterface
    {
        try {
            if (empty($this->colorsFinder)) {
                $this->colorsFinder = new ColorsProductFinder(['collection'=>new BaseCollection()]);
            }
            $this->colorsFinder->load(['id_product'=>$this->id]);
            $collection = $this->colorsFinder->find()->getModels();
            if ($collection->isEmpty() === true) {
                throw new ErrorException($this->emptyError('colors'));
            }
            
            return $collection;
            //return $this->hasMany(ColorsModel::class, ['id'=>'id_color'])->viaTable('products_colors', ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает массив SizesModel, с которыми связан текущий объект ProductsModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getSizes(): CollectionInterface
    {
        try {
            if (empty($this->sizesFinder)) {
                $this->sizesFinder = new SizesProductFinder(['collection'=>new BaseCollection()]);
            }
            $this->sizesFinder->load(['id_product'=>$this->id]);
            $collection = $this->sizesFinder->find()->getModels();
            if ($collection->isEmpty() === true) {
                throw new ErrorException($this->emptyError('sizes'));
            }
            
            return $collection;
            //return $this->hasMany(SizesModel::class, ['id'=>'id_size'])->viaTable('products_sizes', ['id_product'=>'id']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Загружает изображения
     * @param bool $thumbn будут ли созданы эскизы, 
     * по умолчанию true, эскизы будут созданы
     * @return string
     */
    public function upload($thumbn=true): string
    {
        try {
            if (!empty($this->images)) {
                
                $folderName = time();
                
                $directoryPath = \Yii::getAlias('@imagesroot/' . $folderName);
                
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0775);
                }
                
                foreach ($this->images as $image) {
                    PicturesHelper::resize($image);
                    $image->saveAs($directoryPath . '/' . $image->baseName . '.' . $image->extension);
                }
                
                if ($thumbn) {
                    PicturesHelper::createThumbnails($directoryPath);
                }
                
            }
            
            return $folderName ?? '';
        } catch (\Throwable $t) {
            if (!empty($folderName)) {
                PicturesHelper::remove(\Yii::getAlias('@imagesroot/' . $folderName));
            }
            $this->throwException($t, __METHOD__);
        }
    }
}
