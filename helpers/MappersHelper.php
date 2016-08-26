<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\helpers\HashHelper;
use app\mappers\{AddressByAddressMapper,
    AddressByIdMapper,
    AddressInsertMapper,
    AdminMenuMapper,
    BrandsAdminMapper,
    BrandsByBrandMapper,
    BrandsByIdMapper,
    BrandsDeleteMapper,
    BrandsForProductMapper,
    BrandsInsertMapper,
    BrandsMapper,
    BrandsUpdateMapper,
    CategoriesByIdMapper,
    CategoriesByNameMapper,
    CategoriesBySeocodeMapper,
    CategoriesDeleteMapper,
    CategoriesInsertMapper,
    CategoriesMapper,
    CategoriesUpdateMapper,
    ColorsAdminMapper,
    ColorsByColorMapper,
    ColorsByIdMapper,
    ColorsDeleteMapper,
    ColorsForProductMapper,
    ColorsInsertMapper,
    ColorsMapper,
    ColorsUpdateMapper,
    CommentsByIdMapper,
    CommentsDeleteMapper,
    CommentsForProductMapper,
    CommentsInsertMapper,
    CommentsMapper,
    CommentsUpdateMapper,
    CurrencyByCurrencyMapper,
    CurrencyByIdMapper,
    CurrencyByMainMapper,
    CurrencyInsertMapper,
    CurrencyMapper,
    CurrencyUpdateMapper,
    CurrencyUpdateMainNullMapper,
    DeliveriesByIdMapper,
    DeliveriesMapper,
    EmailsByEmailMapper,
    EmailsByIdMapper,
    EmailsInsertMapper,
    EmailsMailingListDeleteMapper,
    EmailsMailingListInsertMapper,
    MailingListByIdMapper,
    MailingListForEmailMapper,
    MailingListMapper,
    PaymentsByIdMapper,
    PaymentsMapper,
    PhonesByIdMapper,
    PhonesByPhoneMapper,
    PhonesInsertMapper,
    ProductDetailMapper,
    ProductsBrandsByIdBrandsMapper,
    ProductsBrandsDeleteMapper,
    ProductsBrandsInsertMapper,
    ProductsByCodeMapper,
    ProductsByIdCategoriesMapper,
    ProductsByIdMapper,
    ProductsByIdSubcategoryMapper,
    ProductsColorsByIdColorsMapper,
    ProductsColorsDeleteMapper,
    ProductsColorsInsertMapper,
    ProductsDeleteMapper,
    ProductsInsertMapper,
    ProductsListMapper,
    ProductsSearchMapper,
    ProductsSizesByIdSizesMapper,
    ProductsSizesDeleteMapper,
    ProductsSizesInsertMapper,
    ProductsUpdateMapper,
    PurchasesForUserMapper,
    PurchasesInsertMapper,
    RelatedProductsMapper,
    RulesMapper,
    SimilarProductsMapper,
    SizesAdminMapper,
    SizesByIdMapper,
    SizesBySizeMapper,
    SizesDeleteMapper,
    SizesForProductMapper,
    SizesInsertMapper,
    SizesMapper,
    SizesUpdateMapper,
    SubcategoryByIdMapper,
    SubcategoryByNameMapper,
    SubcategoryBySeocodeMapper,
    SubcategoryDeleteMapper,
    SubcategoryForCategoryMapper,
    SubcategoryInsertMapper,
    SubcategoryMapper,
    SubcategoryUpdateMapper,
    UsersByIdEmailsMapper,
    UsersByIdMapper,
    UsersInsertMapper,
    UsersRulesInsertMapper,
    UsersUpdateMapper};
use app\models\{AddressModel, 
    BrandsModel,
    CategoriesModel,
    ColorsModel,
    CommentsModel,
    CurrencyModel,
    DeliveriesModel,
    EmailsModel,
    MailingListModel,
    PaymentsModel,
    PhonesModel,
    ProductsBrandsModel,
    ProductsColorsModel,
    ProductsModel,
    ProductsSizesModel,
    SizesModel,
    SubcategoryModel,
    UsersModel};

/**
 * Коллекция методов для работы с БД
 */
class MappersHelper
{
    use ExceptionsTrait;
    
    /**
     * @var array реестр загруженных объектов
     */
    private static $_objectsRegistry = array();
    
    /**
     * Создает новую запись CategoriesModel в БД
     * @param array $categoriesModelArray массив объектов CategoriesModel
     * @return int
     */
    public static function setCategoriesInsert(Array $categoriesModelArray)
    {
        try {
            if (empty($categoriesModelArray) || !$categoriesModelArray[0] instanceof CategoriesModel) {
                throw new ErrorException('Неверный формат данных!');
            }
            $categoriesInsertMapper = new CategoriesInsertMapper([
                'tableName'=>'categories',
                'fields'=>['name', 'seocode'],
                'objectsArray'=>$categoriesModelArray,
            ]);
            if (!$result = $categoriesInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет записи CategoriesModel в БД
     * @param array $categoriesModelArray массив объектов CategoriesModel
     * @return int
     */
    public static function setCategoriesUpdate(Array $categoriesModelArray)
    {
        try {
            if (!is_array($categoriesModelArray) || empty($categoriesModelArray) || !$categoriesModelArray[0] instanceof CategoriesModel) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            $сategoriesUpdateMapper = new CategoriesUpdateMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'objectsArray'=>$categoriesModelArray
            ]);
            $result = $сategoriesUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи CategoriesModel из БД
     * @param array $categoriesModelArray массив CategoriesModel
     * @return int
     */
    public static function setCategoriesDelete(Array $categoriesModelArray)
    {
        try {
            if (empty($categoriesModelArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$categoriesModelArray[0] instanceof CategoriesModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $categoriesDeleteMapper = new CategoriesDeleteMapper([
                'tableName'=>'categories',
                'objectsArray'=>$categoriesModelArray,
            ]);
            if (!$result = $categoriesDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов категорий
     * @return array of objects CategoriesModel
     */
    public static function getCategoriesList()
    {
        try {
            $categoriesMapper = new CategoriesMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'orderByField'=>'name'
            ]);
            $hash = self::createHash([
                CategoriesMapper::className(), 
                $categoriesMapper->tableName, 
                implode('', $categoriesMapper->fields), 
                $categoriesMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $categoriesArray = $categoriesMapper->getGroup();
            if (!is_array($categoriesArray) || empty($categoriesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $categoriesArray);
            return $categoriesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CategoriesModel по id
     * @param object $categoriesModel экземпляр CategoriesModel
     * @return objects CategoriesModel
     */
    public static function getCategoriesById(CategoriesModel $categoriesModel)
    {
        try {
            $categoriesByIdMapper = new CategoriesByIdMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'model'=>$categoriesModel,
            ]);
            $hash = self::createHash([
                CategoriesByIdMapper::className(), 
                $categoriesByIdMapper->tableName, 
                implode('', $categoriesByIdMapper->fields), 
                $categoriesByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $categoriesModel = $categoriesByIdMapper->getOneFromGroup();
            if (!is_object($categoriesModel) || !$categoriesModel instanceof CategoriesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $categoriesModel);
            return $categoriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CategoriesModel по seocode
     * @param object $categoriesModel экземпляр CategoriesModel
     * @return objects CategoriesModel
     */
    public static function getCategoriesBySeocode(CategoriesModel $categoriesModel)
    {
        try {
            $categoriesBySeocodeMapper = new CategoriesBySeocodeMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'model'=>$categoriesModel
            ]);
            $hash = self::createHash([
                CategoriesBySeocodeMapper::className(), 
                $categoriesBySeocodeMapper->tableName, 
                implode('', $categoriesBySeocodeMapper->fields), 
                $categoriesBySeocodeMapper->model->seocode,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $categoriesModel = $categoriesBySeocodeMapper->getOneFromGroup();
            if (!is_object($categoriesModel) || !$categoriesModel instanceof CategoriesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $categoriesModel);
            return $categoriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CategoriesModel по name
     * @param object $categoriesModel экземпляр CategoriesModel
     * @return objects CategoriesModel
     */
    public static function getCategoriesByName(CategoriesModel $categoriesModel)
    {
        try {
            $categoriesByNameMapper = new CategoriesByNameMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'model'=>$categoriesModel
            ]);
            $hash = self::createHash([
                CategoriesByNameMapper::className(), 
                $categoriesByNameMapper->tableName, 
                implode('', $categoriesByNameMapper->fields), 
                $categoriesByNameMapper->model->name,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $categoriesModel = $categoriesByNameMapper->getOneFromGroup();
            if (!is_object($categoriesModel) || !$categoriesModel instanceof CategoriesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $categoriesModel);
            return $categoriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов валют
     * @return array of objects CurrencyModel
     */
    public static function getCurrencyList()
    {
        try {
            $currencyMapper = new CurrencyMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency', 'exchange_rate', 'main'],
                'orderByField'=>'currency'
            ]);
            $hash = self::createHash([
                CurrencyMapper::className(), 
                $currencyMapper->tableName, 
                implode('', $currencyMapper->fields), 
                $currencyMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $currencyArray = $currencyMapper->getGroup();
            if (!is_array($currencyArray) || empty($currencyArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $currencyArray);
            return $currencyArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись CurrencyModel в БД
     * @param array $currencyModelArray массив объектов CurrencyModel
     * @return int
     */
    public static function setCurrencyInsert(Array $currencyModelArray)
    {
        try {
            if (empty($currencyModelArray) || !$currencyModelArray[0] instanceof CurrencyModel) {
                throw new ErrorException('Неверный формат данных!');
            }
            $currencyInsertMapper = new CurrencyInsertMapper([
                'tableName'=>'currency',
                'fields'=>['currency', 'exchange_rate', 'main'],
                'objectsArray'=>$currencyModelArray,
            ]);
            if (!$result = $currencyInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет записи CurrencyModel в БД
     * @param array $currencyModelArray массив объектов CurrencyModel
     * @return int
     */
    public static function setCurrencyUpdate(Array $currencyModelArray)
    {
        try {
            if (!is_array($currencyModelArray) || empty($currencyModelArray) || !$currencyModelArray[0] instanceof CurrencyModel) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            $currencyUpdateMapper = new CurrencyUpdateMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency', 'exchange_rate', 'main'],
                'objectsArray'=>$currencyModelArray
            ]);
            $result = $currencyUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обнуляет поле main у всех CurrencyModel в БД
     * @return int
     */
    public static function setCurrencyUpdateMainNull()
    {
        try {
            $currencyUpdateMainNullMapper = new CurrencyUpdateMainNullMapper([
                'tableName'=>'currency',
                'fields'=>['main'],
            ]);
            if (!$result = $currencyUpdateMainNullMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов colors
     * @param boolean $joinProducts, true - выбирать только те записи, которые связаны с хотя бы одним продуктом
     * @return array of objects ColorsModel
     */
    public static function getColorsList($joinProducts=true)
    {
        try {
            $colorsMapper = new ColorsMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'orderByField'=>'color',
            ]);
            if (!$joinProducts) {
                $colorsMapper->queryClass = 'app\queries\ColorsQueryCreator';
            }
            $hash = self::createHash([
                ColorsMapper::className(), 
                $colorsMapper->tableName, 
                implode('', $colorsMapper->fields), 
                $colorsMapper->orderByField,
                $colorsMapper->queryClass,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $colorsArray = $colorsMapper->getGroup();
            if (!is_array($colorsArray) || empty($colorsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $colorsArray);
            return $colorsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов ColorsModel
     * @return array of objects ColorsModel
     */
    public static function getColorsAdminList()
    {
        try {
            $colorsAdminMapper = new ColorsAdminMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'orderByField'=>'color',
            ]);
            $hash = self::createHash([
                ColorsAdminMapper::className(), 
                $colorsAdminMapper->tableName, 
                implode('', $colorsAdminMapper->fields), 
                $colorsAdminMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $colorsArray = $colorsAdminMapper->getGroup();
            if (!is_array($colorsArray) || empty($colorsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $colorsArray);
            return $colorsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает ColorsModel по id
     * @param object $colorsModel экземпляр ColorsModel
     * @return object ColorsModel
     */
    public static function getColorsById(ColorsModel $colorsModel)
    {
        try {
            $colorsByIdMapper = new ColorsByIdMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'model'=>$colorsModel,
            ]);
            $hash = self::createHash([
                ColorsByIdMapper::className(), 
                $colorsByIdMapper->tableName, 
                implode('', $colorsByIdMapper->fields), 
                $colorsByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $colorsModel = $colorsByIdMapper->getOneFromGroup();
            if (!is_object($colorsModel) || !$colorsModel instanceof ColorsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $colorsModel);
            return $colorsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект ColorsModel по ColorsModel->color
     * @param object $colorsModel экземпляр ColorsModel
     * @return objects ColorsModel
     */
    public static function getColorsByColor(ColorsModel $colorsModel)
    {
        try {
            $colorsByColorMapper = new ColorsByColorMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'model'=>$colorsModel,
            ]);
            $hash = self::createHash([
                ColorsByColorMapper::className(), 
                $colorsByColorMapper->tableName, 
                implode('', $colorsByColorMapper->fields),
                $colorsByColorMapper->model->color,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $colorsModel = $colorsByColorMapper->getOneFromGroup();
            if (!is_object($colorsModel) || !$colorsModel instanceof ColorsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $colorsModel);
            return $colorsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов colors для текущего ProductsModel по id
     * @param object $productsModel экземпляр ProductsModel
     * @return array of objects ColorsModel
     */
    public static function getColorsForProductList(ProductsModel $productsModel)
    {
        try {
            $colorsMapper = new ColorsForProductMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'orderByField'=>'color',
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                ColorsForProductMapper::className(), 
                $colorsMapper->tableName, 
                implode('', $colorsMapper->fields), 
                $colorsMapper->orderByField,
                $colorsMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $colorsArray = $colorsMapper->getGroup();
            if (!is_array($colorsArray) || empty($colorsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $colorsArray);
            return $colorsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов sizes
     * @param boolean $joinProducts, true - выбирать только те записи, которые связаны с хотя бы одним продуктом
     * @return array of objects SizesModel
     */
    public static function getSizesList($joinProducts=true)
    {
        try {
            $sizesMapper = new SizesMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size'
            ]);
            if (!$joinProducts) {
                $sizesMapper->queryClass = 'app\queries\SizesQueryCreator';
            }
            $hash = self::createHash([
                SizesMapper::className(), 
                $sizesMapper->tableName, 
                implode('', $sizesMapper->fields), 
                $sizesMapper->orderByField,
                $sizesMapper->queryClass,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $sizesArray = $sizesMapper->getGroup();
            if (!is_array($sizesArray) || empty($sizesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $sizesArray);
            return $sizesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов SizesModel
     * @return array of objects SizesModel
     */
    public static function getSizesAdminList()
    {
        try {
            $sizesAdminMapper = new SizesAdminMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size'
            ]);
            $hash = self::createHash([
                SizesAdminMapper::className(), 
                $sizesAdminMapper->tableName, 
                implode('', $sizesAdminMapper->fields), 
                $sizesAdminMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $sizesArray = $sizesAdminMapper->getGroup();
            if (!is_array($sizesArray) || empty($sizesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $sizesArray);
            return $sizesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает SizesModel по id
     * @param object $sizesModel экземпляр SizesModel
     * @return object SizesModel
     */
    public static function getSizesById(SizesModel $sizesModel)
    {
         try {
            $sizesByIdMapper = new SizesByIdMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'model'=>$sizesModel,
            ]);
            $hash = self::createHash([
                SizesByIdMapper::className(), 
                $sizesByIdMapper->tableName, 
                implode('', $sizesByIdMapper->fields), 
                $sizesByIdMapper->model->id
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $sizesModel = $sizesByIdMapper->getOneFromGroup();
            if (!is_object($sizesModel) || !$sizesModel instanceof SizesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $sizesModel);
            return $sizesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов sizes для текущего ProductsModel по id
     * @param object $productsModel экземпляр ProductsModel
     * @return array of objects SizesModel
     */
    public static function getSizesForProductList(ProductsModel $productsModel)
    {
        try {
            $sizesForProductMapper = new SizesForProductMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size',
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                SizesForProductMapper::className(), 
                $sizesForProductMapper->tableName, 
                implode('', $sizesForProductMapper->fields), 
                $sizesForProductMapper->orderByField,
                $sizesForProductMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $sizesArray = $sizesForProductMapper->getGroup();
            if (!is_array($sizesArray) || empty($sizesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $sizesArray);
            return $sizesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект SizesModel по SizesModel->size
     * @param object $sizesModel экземпляр SizesModel
     * @return objects SizesModel
     */
    public static function getSizesBySize(SizesModel $sizesModel)
    {
        try {
            $sizesBySizeMapper = new SizesBySizeMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'model'=>$sizesModel,
            ]);
            $hash = self::createHash([
                SizesBySizeMapper::className(), 
                $sizesBySizeMapper->tableName, 
                implode('', $sizesBySizeMapper->fields),
                $sizesBySizeMapper->model->size,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $sizesModel = $sizesBySizeMapper->getOneFromGroup();
            if (!is_object($sizesModel) || !$sizesModel instanceof SizesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $sizesModel);
            return $sizesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись BrandsModel в БД
     * @param array $brandsModelArray массив объектов BrandsModel
     * @return int
     */
    public static function setBrandsInsert(Array $brandsModelArray)
    {
        try {
            if (empty($brandsModelArray) || !$brandsModelArray[0] instanceof BrandsModel) {
                throw new ErrorException('Неверный формат данных!');
            }
            $brandsInsertMapper = new BrandsInsertMapper([
                'tableName'=>'brands',
                'fields'=>['brand'],
                'objectsArray'=>$brandsModelArray,
            ]);
            if (!$result = $brandsInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись ColorsModel в БД
     * @param array $colorsModelArray массив объектов ColorsModel
     * @return int
     */
    public static function setColorsInsert(Array $colorsModelArray)
    {
        try {
            if (empty($colorsModelArray) || !$colorsModelArray[0] instanceof ColorsModel) {
                throw new ErrorException('Неверный формат данных!');
            }
            $colorsInsertMapper = new ColorsInsertMapper([
                'tableName'=>'colors',
                'fields'=>['color'],
                'objectsArray'=>$colorsModelArray,
            ]);
            if (!$result = $colorsInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись SizesModel в БД
     * @param array $sizesModelArray массив объектов SizesModel
     * @return int
     */
    public static function setSizesInsert(Array $sizesModelArray)
    {
        try {
            if (empty($sizesModelArray) || !$sizesModelArray[0] instanceof SizesModel) {
                throw new ErrorException('Неверный формат данных!');
            }
            $sizesInsertMapper = new SizesInsertMapper([
                'tableName'=>'sizes',
                'fields'=>['size'],
                'objectsArray'=>$sizesModelArray,
            ]);
            if (!$result = $sizesInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет записи SizesModel в БД
     * @param array $sizesModelArray массив объектов SizesModel
     * @return int
     */
    public static function setSizesUpdate(Array $sizesModelArray)
    {
        try {
            if (!is_array($sizesModelArray) || empty($sizesModelArray) || !$sizesModelArray[0] instanceof SizesModel) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            $sizesUpdateMapper = new SizesUpdateMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'objectsArray'=>$sizesModelArray
            ]);
            $result = $sizesUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи SizesModel из БД
     * @param array $sizesModelArray массив SizesModel
     * @return int
     */
    public static function setSizesDelete(Array $sizesModelArray)
    {
        try {
            if (empty($sizesModelArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$sizesModelArray[0] instanceof SizesModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $sizesDeleteMapper = new SizesDeleteMapper([
                'tableName'=>'sizes',
                'objectsArray'=>$sizesModelArray,
            ]);
            if (!$result = $sizesDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет записи ColorsModel в БД
     * @param array $colorsModelArray массив объектов ColorsModel
     * @return int
     */
    public static function setColorsUpdate(Array $colorsModelArray)
    {
        try {
            if (!is_array($colorsModelArray) || empty($colorsModelArray) || !$colorsModelArray[0] instanceof ColorsModel) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            $colorsUpdateMapper = new ColorsUpdateMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'objectsArray'=>$colorsModelArray
            ]);
            $result = $colorsUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи ColorsModel из БД
     * @param array $colorsModelArray массив ColorsModel
     * @return int
     */
    public static function setColorsDelete(Array $colorsModelArray)
    {
        try {
            if (empty($colorsModelArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$colorsModelArray[0] instanceof ColorsModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $colorsDeleteMapper = new ColorsDeleteMapper([
                'tableName'=>'colors',
                'objectsArray'=>$colorsModelArray,
            ]);
            if (!$result = $colorsDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов brands
     * @param boolean $joinProducts, true - выбирать только те записи, которые связаны с хотя бы одним продуктом
     * @return array of objects BrandsModel
     */
    public static function getBrandsList($joinProducts=true)
    {
        try {
            $brandsMapper = new BrandsMapper([
                'tableName'=>'brands',
                'fields'=>['id', 'brand'],
                'orderByField'=>'brand',
            ]);
            if (!$joinProducts) {
                $brandsMapper->queryClass = 'app\queries\BrandsQueryCreator';
            }
            $hash = self::createHash([
                BrandsMapper::className(), 
                $brandsMapper->tableName, 
                implode('', $brandsMapper->fields), 
                $brandsMapper->orderByField,
                $brandsMapper->queryClass,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsArray = $brandsMapper->getGroup();
            if (!is_array($brandsArray) || empty($brandsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsArray);
            return $brandsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов BrandsModel
     * @return array of objects BrandsModel
     */
    public static function getBrandsAdminList()
    {
        try {
            $brandsAdminMapper = new BrandsAdminMapper([
                'tableName'=>'brands',
                'fields'=>['id', 'brand'],
                'orderByField'=>'brand',
            ]);
            $hash = self::createHash([
                BrandsAdminMapper::className(), 
                $brandsAdminMapper->tableName, 
                implode('', $brandsAdminMapper->fields), 
                $brandsAdminMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsArray = $brandsAdminMapper->getGroup();
            if (!is_array($brandsArray) || empty($brandsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsArray);
            return $brandsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает BrandsModel по ProductsModel::id
     * @param object $productsModel экземпляр ProductsModel
     * @return object BrandsModel
     */
    public static function getBrandsForProduct(ProductsModel $productsModel)
    {
        try {
            $brandsForProductMapper = new BrandsForProductMapper([
                'tableName'=>'brands',
                'fields'=>['id', 'brand'],
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                BrandsForProductMapper::className(), 
                $brandsForProductMapper->tableName, 
                implode('', $brandsForProductMapper->fields), 
                $brandsForProductMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsModel = $brandsForProductMapper->getOneFromGroup();
            if (!is_object($brandsModel) || !$brandsModel instanceof BrandsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsModel);
            return $brandsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект BrandsModel по BrandsModel->id
     * @param object $brandsModel экземпляр BrandsModel
     * @return objects BrandsModel
     */
    public static function getBrandsById(BrandsModel $brandsModel)
    {
        try {
            $brandsByIdMapper = new BrandsByIdMapper([
                'tableName'=>'brands',
                'fields'=>['id', 'brand'],
                'model'=>$brandsModel,
            ]);
            $hash = self::createHash([
                BrandsByIdMapper::className(), 
                $brandsByIdMapper->tableName, 
                implode('', $brandsByIdMapper->fields), 
                $brandsByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsModel = $brandsByIdMapper->getOneFromGroup();
            if (!is_object($brandsModel) || !$brandsModel instanceof BrandsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsModel);
            return $brandsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект BrandsModel по BrandsModel->brand
     * @param object $brandsModel экземпляр BrandsModel
     * @return objects BrandsModel
     */
    public static function getBrandsByBrand(BrandsModel $brandsModel)
    {
        try {
            $brandsByBrandMapper = new BrandsByBrandMapper([
                'tableName'=>'brands',
                'fields'=>['id', 'brand'],
                'model'=>$brandsModel,
            ]);
            $hash = self::createHash([
                BrandsByBrandMapper::className(), 
                $brandsByBrandMapper->tableName, 
                implode('', $brandsByBrandMapper->fields),
                $brandsByBrandMapper->model->brand,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsModel = $brandsByBrandMapper->getOneFromGroup();
            if (!is_object($brandsModel) || !$brandsModel instanceof BrandsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsModel);
            return $brandsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет записи BrandsModel в БД
     * @param array $brandsModelArray массив объектов BrandsModel
     * @return int
     */
    public static function setBrandsUpdate(Array $brandsModelArray)
    {
        try {
            if (!is_array($brandsModelArray) || empty($brandsModelArray) || !$brandsModelArray[0] instanceof BrandsModel) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            $brandsUpdateMapper = new BrandsUpdateMapper([
                'tableName'=>'brands',
                'fields'=>['id', 'brand'],
                'objectsArray'=>$brandsModelArray
            ]);
            $result = $brandsUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи BrandsModel из БД
     * @param array $brandsModelArray массив BrandsModel
     * @return int
     */
    public static function setBrandsDelete(Array $brandsModelArray)
    {
        try {
            if (empty($brandsModelArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$brandsModelArray[0] instanceof BrandsModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $brandsDeleteMapper = new BrandsDeleteMapper([
                'tableName'=>'brands',
                'objectsArray'=>$brandsModelArray,
            ]);
            if (!$result = $brandsDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект AddressModel по address
     * @return objects AddressModel
     */
    public static function getAddressByAddress(AddressModel $addressModel)
    {
        try {
            $addressByAddressMapper = new AddressByAddressMapper([
                'tableName'=>'address',
                'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                'model'=>$addressModel
            ]);
            $hash = self::createHash([
                AddressByAddressMapper::className(), 
                $addressByAddressMapper->tableName, 
                implode('', $addressByAddressMapper->fields), 
                $addressByAddressMapper->model->address,
                $addressByAddressMapper->model->city,
                $addressByAddressMapper->model->country,
                $addressByAddressMapper->model->postcode,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $addressModel = $addressByAddressMapper->getOneFromGroup();
            if (!is_object($addressModel) || !$addressModel instanceof AddressModel) {
                return null;
            }
            self::createRegistryEntry($hash, $addressModel);
            return $addressModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись AddressModel в БД
     * @param object $addressModel экземпляр AddressModel
     * @return int
     */
    public static function setAddressInsert(AddressModel $addressModel)
    {
        try {
            $addressInsertMapper = new AddressInsertMapper([
                'tableName'=>'address',
                'fields'=>['address', 'city', 'country', 'postcode'],
                'objectsArray'=>[$addressModel],
            ]);
            $result = $addressInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект AddressModel по id
     * @param object $addressModel экземпляр AddressModel
     * @return objects AddressModel
     */
    public static function getAddressById(AddressModel $addressModel)
    {
        try {
            $addressByIdMapper = new AddressByIdMapper([
                'tableName'=>'address',
                'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                'model'=>$addressModel,
            ]);
            $hash = self::createHash([
                AddressByIdMapper::className(), 
                $addressByIdMapper->tableName, 
                implode('', $addressByIdMapper->fields), 
                $addressByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $addressModel = $addressByIdMapper->getOneFromGroup();
            if (!is_object($addressModel) || !$addressModel instanceof AddressModel) {
                return null;
            }
            self::createRegistryEntry($hash, $addressModel);
            return $addressModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект PhonesModel по phone
     * @return objects PhonesModel
     */
    public static function getPhonesByPhone(PhonesModel $phonesModel)
    {
        try {
            $phonesByPhoneMapper = new PhonesByPhoneMapper([
                'tableName'=>'phones',
                'fields'=>['id', 'phone'],
                'model'=>$phonesModel
            ]);
            $hash = self::createHash([
                PhonesByPhoneMapper::className(), 
                $phonesByPhoneMapper->tableName, 
                implode('', $phonesByPhoneMapper->fields), 
                $phonesByPhoneMapper->model->phone,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $phonesModel = $phonesByPhoneMapper->getOneFromGroup();
            if (!is_object($phonesModel) || !$phonesModel instanceof PhonesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $phonesModel);
            return $phonesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись PhonesModel в БД
     * @param object $phonesModel экземпляр PhonesModel
     * @return int
     */
    public static function setPhonesInsert(PhonesModel $phonesModel)
    {
        try {
           $phonesInsertMapper = new PhonesInsertMapper([
                'tableName'=>'phones',
                'fields'=>['phone'],
                'objectsArray'=>[$phonesModel],
            ]);
            $result = $phonesInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает PhonesModel по id
     * @param object $phonesModel экземпляр PhonesModel
     * @return object
     */
    public static function getPhonesById(PhonesModel $phonesModel)
    {
        try {
            $phonesByIdMapper = new PhonesByIdMapper([
                'tableName'=>'phones',
                'fields'=>['id', 'phone'],
                'model'=>$phonesModel,
            ]);
            $hash = self::createHash([
                PhonesByIdMapper::className(), 
                $phonesByIdMapper->tableName, 
                implode('', $phonesByIdMapper->fields), 
                $phonesByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $phonesModel = $phonesByIdMapper->getOneFromGroup();
            if (!is_object($phonesModel) || !$phonesModel instanceof PhonesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $phonesModel);
            return $phonesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
      * Получает DeliveriesModel по id
     * @param object $deliveriesModel экземпляр DeliveriesModel
     * @return object
     */
    public static function getDeliveriesById(DeliveriesModel $deliveriesModel)
    {
        try {
            $deliveriesByIdMapper = new DeliveriesByIdMapper([
                'tableName'=>'deliveries',
                'fields'=>['id', 'name', 'description', 'price'],
                'model'=>$deliveriesModel,
            ]);
            $hash = self::createHash([
                DeliveriesByIdMapper::className(), 
                $deliveriesByIdMapper->tableName, 
                implode('', $deliveriesByIdMapper->fields), 
                $deliveriesByIdMapper->model->id
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $deliveriesModel = $deliveriesByIdMapper->getOneFromGroup();
            if (!is_object($deliveriesModel) || !$deliveriesModel instanceof DeliveriesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $deliveriesModel);
            return $deliveriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов deliveries
     * @return array of objects DeliveriesModel
     */
    public static function getDeliveriesList()
    {
        try {
            $deliveriesMapper = new DeliveriesMapper([
                'tableName'=>'deliveries',
                'fields'=>['id', 'name', 'description', 'price'],
                'orderByField'=>'id'
            ]);
            $hash = self::createHash([
                DeliveriesMapper::className(), 
                $deliveriesMapper->tableName, 
                implode('', $deliveriesMapper->fields), 
                $deliveriesMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $deliveriesArray = $deliveriesMapper->getGroup();
            if (!is_array($deliveriesArray) || empty($deliveriesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $deliveriesArray);
            return $deliveriesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает PaymentsModel по id
     * @param object $paymentsModel экземпляр PaymentsModel
     * @return object
     */
    public static function getPaymentsById(PaymentsModel $paymentsModel)
    {
        try {
            $paymentsByIdMapper = new PaymentsByIdMapper([
                'tableName'=>'payments',
                'fields'=>['id', 'name', 'description'],
                'model'=>$paymentsModel,
            ]);
            $hash = self::createHash([
                PaymentsByIdMapper::className(), 
                $paymentsByIdMapper->tableName, 
                implode('', $paymentsByIdMapper->fields), 
                $paymentsByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $paymentsModel = $paymentsByIdMapper->getOneFromGroup();
            if (!is_object($paymentsModel) || !$paymentsModel instanceof PaymentsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $paymentsModel);
            return $paymentsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов payments
     * @return array of objects PaymentsModel
     */
    public static function getPaymentsList()
    {
        try {
            $paymentsMapper = new PaymentsMapper([
                'tableName'=>'payments',
                'fields'=>['id', 'name', 'description'],
            ]);
            $hash = self::createHash([
                PaymentsMapper::className(), 
                $paymentsMapper->tableName, 
                implode('', $paymentsMapper->fields), 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $paymentsArray = $paymentsMapper->getGroup();
            if (!is_array($paymentsArray) || empty($paymentsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $paymentsArray);
            return $paymentsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись PurchasesModel в БД, вязывающую пользователя с купленным товаром
     * @return boolean
     */
    public static function setPurchasesInsert()
    {
        try {
            $id_users = \Yii::$app->cart->user->id;
            $brandsArray = \Yii::$app->cart->getProductsArray();
            $id_deliveries = \Yii::$app->cart->user->deliveries->id;
            $id_payments = \Yii::$app->cart->user->payments->id;
            
            if (empty($id_users)) {
                throw new ErrorException('Отсутствует cart->user->id!');
            }
            if (!is_array($brandsArray) || empty($brandsArray)) {
                throw new ErrorException('Отсутствуют данные в массиве cart->brandsArray!');
            }
            if (empty($id_deliveries)) {
                throw new ErrorException('Отсутствует user->deliveries->id!');
            }
            if (empty($id_payments)) {
                throw new ErrorException('Отсутствует user->payments->id!');
            }
            
            $arrayToDb = [];
            foreach ($brandsArray as $product) {
                $arrayToDb[] = ['id_users'=>$id_users, 'id_products'=>$product->id, 'quantity'=>$product->quantity, 'id_colors'=>$product->colorToCart, 'id_sizes'=>$product->sizeToCart, 'id_deliveries'=>$id_deliveries, 'id_payments'=>$id_payments, 'received'=>true];
            }
            
            $usersPurchasesInsertMapper = new PurchasesInsertMapper([
                'tableName'=>'purchases',
                'fields'=>['id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $usersPurchasesInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов purchases для текущего UsersModel по id
     * @param object $usersModel экземпляр UsersModel
     * @return array of objects PurchasesModel
     */
    public static function getPurchasesForUserList(UsersModel $usersModel)
    {
        try {
            $purchasesForUserMapper = new PurchasesForUserMapper([
                'tableName'=>'purchases',
                'fields'=>['id', 'id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date', 'processed', 'canceled', 'shipped'],
                'orderByField'=>'received_date',
                'orderByType'=>'DESC',
                'model'=>$usersModel,
            ]);
            $hash = self::createHash([
                PurchasesForUserMapper::className(), 
                $purchasesForUserMapper->tableName, 
                implode('', $purchasesForUserMapper->fields), 
                $purchasesForUserMapper->orderByField, 
                $purchasesForUserMapper->orderByType, 
                $purchasesForUserMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $purchasesArray = $purchasesForUserMapper->getGroup();
            if (!is_array($purchasesArray) || empty($purchasesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $purchasesArray);
            return $purchasesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет запись в БД объектом UsersModel
     * @param array $usersModelArray массив экземпляров UsersModel
     * @return int
     */
    public static function setUsersUpdate(Array $usersModelArray)
    {
        try {
            $usersUpdateMapper = new UsersUpdateMapper([
                'tableName'=>'users',
                'fields'=>['id', 'id_emails', 'name', 'surname', 'id_phones', 'id_address'],
                'objectsArray'=>$usersModelArray,
            ]);
            $result = $usersUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись UsersModel в БД
     * @param object $usersModel экземпляр UsersModel
     * @return int
     */
    public static function setUsersInsert(UsersModel $usersModel)
    {
        try {
            $usersInsertMapper = new UsersInsertMapper([
                'tableName'=>'users',
                'fields'=>['id_emails', 'password', 'name', 'surname', 'id_phones', 'id_address'],
                'objectsArray'=>[$usersModel],
            ]);
            $result = $usersInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект EmailsModel по email
     * @return objects EmailsModel
     */
    public static function getEmailsByEmail(EmailsModel $emailsModel)
    {
        try {
            $emailsByEmailMapper = new EmailsByEmailMapper([
                'tableName'=>'emails',
                'fields'=>['id', 'email'],
                'model'=>$emailsModel
            ]);
            $hash = self::createHash([
                EmailsByEmailMapper::className(), 
                $emailsByEmailMapper->tableName, 
                implode('', $emailsByEmailMapper->fields), 
                $emailsByEmailMapper->model->email, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $emailsModel = $emailsByEmailMapper->getOneFromGroup();
            if (!is_object($emailsModel) || !$emailsModel instanceof EmailsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $emailsModel);
            return $emailsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись EmailsModel в БД
     * @param object $emailsModel экземпляр EmailsModel
     * @return int
     */
    public static function setEmailsInsert(EmailsModel $emailsModel)
    {
        try {
            $emailsInsertMapper = new EmailsInsertMapper([
                'tableName'=>'emails',
                'fields'=>['email'],
                'objectsArray'=>[$emailsModel],
            ]);
            $result = $emailsInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает EmailsModel по id
     * @param object $emailsModel экземпляр EmailsModel
     * @return object
     */
    public static function getEmailsById(EmailsModel $emailsModel)
    {
        try {
            $emailsByIdMapper = new EmailsByIdMapper([
                'tableName'=>'emails',
                'fields'=>['id', 'email'],
                'model'=>$emailsModel,
            ]);
            $hash = self::createHash([
                EmailsByIdMapper::className(), 
                $emailsByIdMapper->tableName, 
                implode('', $emailsByIdMapper->fields), 
                $emailsByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $emailsModel = $emailsByIdMapper->getOneFromGroup();
            if (!is_object($emailsModel) || !$emailsModel instanceof EmailsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $emailsModel);
            return $emailsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись в БД, связывающую пользователя с правами доступа
     * @param object $usersModel экземпляр UsersModel
     * @return int
     */
    public static function setUsersRulesInsert(UsersModel $usersModel)
    {
        try {
            $usersRulesInsertMapper = new UsersRulesInsertMapper([
                'tableName'=>'users_rules',
                'fields'=>['id_users', 'id_rules'],
                'model'=>$usersModel
            ]);
            if (!$result = $usersRulesInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CurrencyModel по id
     * @param object $currencyModel экземпляр CurrencyModel
     * @return objects CurrencyModel
     */
    public static function getCurrencyById(CurrencyModel $currencyModel)
    {
        try {
            $currencyByIdMapper = new CurrencyByIdMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency', 'exchange_rate', 'main'],
                'model'=>$currencyModel,
            ]);
            $hash = self::createHash([
                CurrencyByIdMapper::className(), 
                $currencyByIdMapper->tableName, 
                implode('', $currencyByIdMapper->fields), 
                $currencyByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $currencyModel = $currencyByIdMapper->getOneFromGroup();
            if (!is_object($currencyModel) || !$currencyModel instanceof CurrencyModel) {
                return null;
            }
            self::createRegistryEntry($hash, $currencyModel);
            return $currencyModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CurrencyModel по main
     * @param object $currencyModel экземпляр CurrencyModel
     * @return objects CurrencyModel
     */
    public static function getCurrencyByMain()
    {
        try {
            $currencyByMainMapper = new CurrencyByMainMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            ]);
            $hash = self::createHash([
                CurrencyByMainMapper::className(), 
                $currencyByMainMapper->tableName, 
                implode('', $currencyByMainMapper->fields), 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $currencyModel = $currencyByMainMapper->getOneFromGroup();
            if (!is_object($currencyModel) || !$currencyModel instanceof CurrencyModel) {
                return null;
            }
            self::createRegistryEntry($hash, $currencyModel);
            return $currencyModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CurrencyModel по CurrencyModel->currency
     * @param object $currencyModel экземпляр CurrencyModel
     * @return objects CurrencyModel
     */
    public static function getCurrencyByCurrency(CurrencyModel $currencyModel)
    {
        try {
            $currencyByCurrencyMapper = new CurrencyByCurrencyMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency', 'exchange_rate', 'main'],
                'model'=>$currencyModel,
            ]);
            $hash = self::createHash([
                CurrencyByCurrencyMapper::className(), 
                $currencyByCurrencyMapper->tableName, 
                implode('', $currencyByCurrencyMapper->fields),
                $currencyByCurrencyMapper->model->currency,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $currencyModel = $currencyByCurrencyMapper->getOneFromGroup();
            if (!is_object($currencyModel) || !$currencyModel instanceof CurrencyModel) {
                return null;
            }
            self::createRegistryEntry($hash, $currencyModel);
            return $currencyModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись комментария в БД
     * @param object $commentsModel экземпляр CommentsModel
     * @return int
     */
    public static function setCommentsInsert(CommentsModel $commentsModel)
    {
        try {
            $commentsInsertMapper = new CommentsInsertMapper([
                'tableName'=>'comments',
                'fields'=>['date', 'text', 'name', 'id_emails', 'id_products'],
                'objectsArray'=>[$commentsModel],
            ]);
            if (!$result = $commentsInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет запись CommentsModel в БД
     * @param array $updateConfig массив конфигурации, может включать в себя
     * - modelArray массив экземпляров CommentsModel, обязательный
     * - fields массив полей, которые должны быть обновлены
     * @return int
     */
    public static function setCommentsUpdate(Array $updateConfig)
    {
        try {
            if (empty($updateConfig)) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            if (!is_array($updateConfig['modelArray']) || empty($updateConfig['modelArray']) || !$updateConfig['modelArray'][0] instanceof CommentsModel) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            $config = [
                'tableName'=>'comments',
                'fields'=>['id', 'date', 'text', 'name', 'id_emails', 'id_products', 'active'],
                'objectsArray'=>$updateConfig['modelArray'],
            ];
            if (!empty($updateConfig['fields'])) {
                if (!is_array($updateConfig['fields'])) {
                    throw new ErrorException('Переданы некорректные данные!');
                }
                $config['fields'] = $updateConfig['fields'];
            }
            $commentsUpdateMapper = new CommentsUpdateMapper($config);
            $result = $commentsUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи CommentsModel из БД
     * @param array $commentsModelArray массив CommentsModel
     * @return int
     */
    public static function setCommentsDelete(Array $commentsModelArray)
    {
        try {
            if (empty($commentsModelArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$commentsModelArray[0] instanceof CommentsModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $commentsDeleteMapper = new CommentsDeleteMapper([
                'tableName'=>'comments',
                'objectsArray'=>$commentsModelArray,
            ]);
            if (!$result = $commentsDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив ProductsModel
     * @param array $config массив настроек для маппера
     * @return array of objects ProductsModel
     */
    public static function getProductsList($config)
    {
        try {
            $productsMapper = new ProductsListMapper($config);
            $hash = self::createHash([
                ProductsListMapper::className(), 
                $productsMapper->tableName, 
                implode('', $productsMapper->fields), 
                serialize($productsMapper->otherTablesFields), 
                $productsMapper->orderByField, 
                $productsMapper->getDataSorting, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsArray = $productsMapper->getGroup();
            if (!is_array($brandsArray) || empty($brandsArray) || !$brandsArray[0] instanceof ProductsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsArray);
            return $brandsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив ProductsModel при поиске
     * @param array $config массив настроек для маппера
     * @return array of objects ProductsModel
     */
    public static function getProductsSearch($config)
    {
        try {
            $productsSearchMapper = new ProductsSearchMapper($config);
            $hash = self::createHash([
                ProductsSearchMapper::className(), 
                $productsSearchMapper->tableName, 
                implode('', $productsSearchMapper->fields), 
                $productsSearchMapper->orderByField, 
                $productsSearchMapper->orderByType, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsArray = $productsSearchMapper->getGroup();
            if (!is_array($brandsArray) || empty($brandsArray) || !$brandsArray[0] instanceof ProductsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsArray);
            return $brandsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект ProductsModel по code
     * @param object $productsModel экземпляр ProductsModel
     * @return objects ProductsModel
     */
    public static function getProductsByCode(ProductsModel $productsModel)
    {
        try {
            $productsByCodeMapper = new ProductsByCodeMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'code', 'name', 'description', 'price', 'images'],
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                ProductsByCodeMapper::className(), 
                $productsByCodeMapper->tableName, 
                implode('', $productsByCodeMapper->fields), 
                $productsByCodeMapper->model->code, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $productsModel = $productsByCodeMapper->getOneFromGroup();
            if (!is_object($productsModel) || !$productsModel instanceof ProductsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $productsModel);
            return $productsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект ProductsModel по id
     * @param object $productsModel экземпляр ProductsModel
     * @return objects ProductsModel
     */
    public static function getProductsById(ProductsModel $productsModel)
    {
        try {
            $productsByIdMapper = new ProductsByIdMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active', 'total_products'],
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                ProductsByIdMapper::className(), 
                $productsByIdMapper->tableName, 
                implode('', $productsByIdMapper->fields), 
                $productsByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $productsModel = $productsByIdMapper->getOneFromGroup();
            if (!is_object($productsModel) || !$productsModel instanceof ProductsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $productsModel);
            return $productsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов ProductsModel по CategoriesModel->id
     * @param object $categoriesModel экземпляр CategoriesModel
     * @return array ProductsModel
     */
    public static function getProductsByIdCategories(CategoriesModel $categoriesModel)
    {
        try {
            $productsByIdCategoriesMapper = new ProductsByIdCategoriesMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'code', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active', 'total_products'],
                'model'=>$categoriesModel,
            ]);
            $hash = self::createHash([
                ProductsByIdCategoriesMapper::className(), 
                $productsByIdCategoriesMapper->tableName, 
                implode('', $productsByIdCategoriesMapper->fields), 
                $productsByIdCategoriesMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsArray = $productsByIdCategoriesMapper->getGroup();
            if (!is_array($brandsArray) || empty($brandsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsArray);
            return $brandsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов ProductsModel по SubcategoryModel->id
     * @param object $subcategoryModel экземпляр SubcategoryModel
     * @return array ProductsModel
     */
    public static function getProductsByIdSubcategory(SubcategoryModel $subcategoryModel)
    {
        try {
            $productsByIdSubcategoryMapper = new ProductsByIdSubcategoryMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'code', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active', 'total_products'],
                'model'=>$subcategoryModel,
            ]);
            $hash = self::createHash([
                ProductsByIdSubcategoryMapper::className(), 
                $productsByIdSubcategoryMapper->tableName, 
                implode('', $productsByIdSubcategoryMapper->fields), 
                $productsByIdSubcategoryMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $brandsArray = $productsByIdSubcategoryMapper->getGroup();
            if (!is_array($brandsArray) || empty($brandsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsArray);
            return $brandsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись ProductsModel в БД
     * @param object $productsModel экземпляр ProductsModel
     * @return int
     */
    public static function setProductsInsert(ProductsModel $productsModel)
    {
        try {
            $productsInsertMapper = new ProductsInsertMapper([
                'tableName'=>'products',
                'fields'=>['date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active', 'total_products'],
                'objectsArray'=>[$productsModel],
            ]);
            $result = $productsInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет запись ProductsModel в БД
     * @param array $updateConfig массив конфигурации, может включать в себя
     * - modelArray массив экземпляров ProductsModel, обязательный
     * - fields массив полей, которые должны быть обновлены
     * - images bool, указывает, необходимо ли обновлять поле images
     * @return int
     */
    public static function setProductsUpdate(Array $updateConfig)
    {
        try {
            if (empty($updateConfig)) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            if (!is_array($updateConfig['modelArray']) || empty($updateConfig['modelArray']) || !$updateConfig['modelArray'][0] instanceof ProductsModel) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            $config = [
                'tableName'=>'products',
                'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'id_categories', 'id_subcategory', 'active', 'total_products'],
                'objectsArray'=>$updateConfig['modelArray'],
            ];
            if (!empty($updateConfig['fields'])) {
                if (!is_array($updateConfig['fields'])) {
                    throw new ErrorException('Переданы некорректные данные!');
                }
                $config['fields'] = $updateConfig['fields'];
            }
            if (!empty($updateConfig['images'])) {
                $config['fields'][] = 'images';
            }
            $productsUpdateMapper = new ProductsUpdateMapper($config);
            $result = $productsUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи ProductsModel из БД
     * @param array $productsModelArray массив ProductsModel
     * @return int
     */
    public static function setProductsDelete(Array $productsModelArray)
    {
        try {
            if (empty($productsModelArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$productsModelArray[0] instanceof ProductsModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $productsDeleteMapper = new ProductsDeleteMapper([
                'tableName'=>'products',
                'objectsArray'=>$productsModelArray,
            ]);
            if (!$result = $productsDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект UsersModel по id_emails
     * @param object $usersModel экземпляр UsersModel
     * @return objects UsersModel
     */
    public static function getUsersByIdEmails(UsersModel $usersModel)
    {
        try {
            $usersByLoginMapper = new UsersByIdEmailsMapper([
                'tableName'=>'users',
                'fields'=>['id', 'id_emails', 'password', 'name', 'surname', 'id_phones', 'id_address'],
                'model'=>$usersModel
            ]);
            $hash = self::createHash([
                UsersByIdEmailsMapper::className(), 
                $usersByLoginMapper->tableName, 
                implode('', $usersByLoginMapper->fields), 
                $usersByLoginMapper->model->id_emails, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $usersModel = $usersByLoginMapper->getOneFromGroup();
            if (!is_object($usersModel) || !$usersModel instanceof UsersModel) {
                return null;
            }
            self::createRegistryEntry($hash, $usersModel);
            return $usersModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект UsersModel по id
     * @param object $usersModel экземпляр UsersModel
     * @return objects UsersModel
     */
    public static function getUsersById(UsersModel $usersModel)
    {
        try {
            $usersByIdMapper = new UsersByIdMapper([
                'tableName'=>'users',
                'fields'=>['id', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                'model'=>$usersModel,
            ]);
            $hash = self::createHash([
                UsersByIdMapper::className(), 
                $usersByIdMapper->tableName, 
                implode('', $usersByIdMapper->fields), 
                $usersByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $usersModel = $usersByIdMapper->getOneFromGroup();
            if (!is_object($usersModel) || !$usersModel instanceof UsersModel) {
                return null;
            }
            self::createRegistryEntry($hash, $usersModel);
            return $usersModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов rules
     * @return array of objects RulesModel
     */
    public static function getRulesList()
    {
        try {
            $rulesMapper = new RulesMapper([
                'tableName'=>'rules',
                'fields'=>['id', 'rule'],
                'orderByField'=>'rule',
            ]);
            $hash = self::createHash([
                RulesMapper::className(), 
                $rulesMapper->tableName, 
                implode('', $rulesMapper->fields), 
                $rulesMapper->orderByField, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $rulesArray = $rulesMapper->getGroup();
            if (!is_array($rulesArray) || empty($rulesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $rulesArray);
            return $rulesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись SubcategoryModel в БД
     * @param array $subcategoryModels массив объектов SubcategoryModel
     * @return int
     */
    public static function setSubcategoryInsert(Array $subcategoryModels)
    {
        try {
            if (empty($subcategoryModels) || !$subcategoryModels[0] instanceof SubcategoryModel) {
                throw new ErrorException('Неверный формат данных!');
            }
            $subcategoryInsertMapper = new SubcategoryInsertMapper([
                'tableName'=>'subcategory',
                'fields'=>['name', 'seocode', 'id_categories'],
                'objectsArray'=>$subcategoryModels,
            ]);
            if (!$result = $subcategoryInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет записи SubcategoryModel в БД
     * @param array $subcategoryModels массив объектов SubcategoryModel
     * @return int
     */
    public static function setSubcategoryUpdate(Array $subcategoryModels)
    {
        try {
            if (!is_array($subcategoryModels) || empty($subcategoryModels) || !$subcategoryModels[0] instanceof SubcategoryModel) {
                throw new ErrorException('Переданы некорректные данные!');
            }
            $subcategoryUpdateMapper = new SubcategoryUpdateMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'objectsArray'=>$subcategoryModels
            ]);
            $result = $subcategoryUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи SubcategoryModel из БД
     * @param array $subcategoryModelArray массив SubcategoryModel
     * @return int
     */
    public static function setSubcategoryDelete(Array $subcategoryModelArray)
    {
        try {
            if (empty($subcategoryModelArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$subcategoryModelArray[0] instanceof SubcategoryModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $subcategoryDeleteMapper = new SubcategoryDeleteMapper([
                'tableName'=>'subcategory',
                'objectsArray'=>$subcategoryModelArray,
            ]);
            if (!$result = $subcategoryDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов SubcategoryModel по id CategoriesModel
     * @return array of objects SubcategoryModel
     */
    public static function getSubcategoryForCategoryList(CategoriesModel $categoriesModel)
    {
        try {
            $subcategoryForCategoryMapper = new SubcategoryForCategoryMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'model'=>$categoriesModel
            ]);
            $hash = self::createHash([
                SubcategoryForCategoryMapper::className(), 
                $subcategoryForCategoryMapper->tableName, 
                implode('', $subcategoryForCategoryMapper->fields), 
                $subcategoryForCategoryMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $subcategoryArray = $subcategoryForCategoryMapper->getGroup();
            if (!is_array($subcategoryArray) || empty($subcategoryArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $subcategoryArray);
            return $subcategoryArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов SubcategoryModel
     * @return array of objects SubcategoryModel
     */
    public static function getSubcategoryList()
    {
        try {
            $subcategoryMapper = new SubcategoryMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'orderByField'=>'name'
            ]);
            $hash = self::createHash([
                SubcategoryMapper::className(), 
                $subcategoryMapper->tableName, 
                implode('', $subcategoryMapper->fields), 
                $subcategoryMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $subcategoryArray = $subcategoryMapper->getGroup();
            if (!is_array($subcategoryArray) || empty($subcategoryArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $subcategoryArray);
            return $subcategoryArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект SubcategoryModel по id
     * @param object $subcategoryModel экземпляр SubcategoryModel
     * @return objects SubcategoryModel
     */
    public static function getSubcategoryById(SubcategoryModel $subcategoryModel)
    {
        try {
            $subcategoryByIdMapper = new SubcategoryByIdMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'model'=>$subcategoryModel,
            ]);
            $hash = self::createHash([
                SubcategoryByIdMapper::className(), 
                $subcategoryByIdMapper->tableName, 
                implode('', $subcategoryByIdMapper->fields), 
                $subcategoryByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $subcategoryModel = $subcategoryByIdMapper->getOneFromGroup();
            if (!is_object($subcategoryModel) || !$subcategoryModel instanceof SubcategoryModel) {
                return null;
            }
            self::createRegistryEntry($hash, $subcategoryModel);
            return $subcategoryModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
     /**
     * Получает объект SubcategoryModel по seocode
     * @param object $subcategoryModel экземпляр SubcategoryModel
     * @return objects SubcategoryModel
     */
    public static function getSubcategoryBySeocode(SubcategoryModel $subcategoryModel)
    {
        try {
            $subcategoryBySeocodeMapper = new SubcategoryBySeocodeMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'model'=>$subcategoryModel,
            ]);
            $hash = self::createHash([
                SubcategoryBySeocodeMapper::className(), 
                $subcategoryBySeocodeMapper->tableName, 
                implode('', $subcategoryBySeocodeMapper->fields), 
                $subcategoryBySeocodeMapper->model->seocode, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $subcategoryModel = $subcategoryBySeocodeMapper->getOneFromGroup();
            if (!is_object($subcategoryModel) || !$subcategoryModel instanceof SubcategoryModel) {
                return null;
            }
            self::createRegistryEntry($hash, $subcategoryModel);
            return $subcategoryModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект SubcategoryModel по name
     * @param object $subcategoryModel экземпляр SubcategoryModel
     * @return objects SubcategoryModel
     */
    public static function getSubcategoryByName(SubcategoryModel $subcategoryModel)
    {
        try {
            $subcategoryByNameMapper = new SubcategoryByNameMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'model'=>$subcategoryModel
            ]);
            $hash = self::createHash([
                SubcategoryByNameMapper::className(), 
                $subcategoryByNameMapper->tableName, 
                implode('', $subcategoryByNameMapper->fields), 
                $subcategoryByNameMapper->model->name,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $subcategoryModel = $subcategoryByNameMapper->getOneFromGroup();
            if (!is_object($subcategoryModel) || !$subcategoryModel instanceof SubcategoryModel) {
                return null;
            }
            self::createRegistryEntry($hash, $subcategoryModel);
            return $subcategoryModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов products, похожих свойствами с текущим ProductsModel 
     * @return array of objects ProductsModel
     */
    public static function getSimilarProductsList(ProductsModel $productsModel)
    {
        try {
            $similarProductsMapper = new SimilarProductsMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'name', 'price', 'images'],
                'orderByField'=>'date',
                'getDataSorting'=>false,
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                SimilarProductsMapper::className(), 
                $similarProductsMapper->tableName, 
                implode('', $similarProductsMapper->fields), 
                $similarProductsMapper->orderByField, 
                serialize($similarProductsMapper->otherTablesFields),
                $similarProductsMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $similarsArray = $similarProductsMapper->getGroup();
            if (!is_array($similarsArray) || empty($similarsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $similarsArray);
            return $similarsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов ProductsModel, связанных с текущим ProductsModel по id
     * @return array of objects ProductsModel
     */
    public static function getRelatedProductsList(ProductsModel $productsModel)
    {
        try {
            $relatedProductsMapper = new RelatedProductsMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'name', 'price', 'images'],
                'orderByField'=>'date',
                'getDataSorting'=>false,
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'orderByField'=>'date',
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                RelatedProductsMapper::className(), 
                $relatedProductsMapper->tableName, 
                implode('', $relatedProductsMapper->fields), 
                $relatedProductsMapper->orderByField, 
                serialize($relatedProductsMapper->otherTablesFields),
                $relatedProductsMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $relatedArray = $relatedProductsMapper->getGroup();
            if (!is_array($relatedArray) || empty($relatedArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $relatedArray);
            return $relatedArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов comments для текущего ProductsModel по id
     * @param object $productsModel экземпляр ProductsModel
     * @return array of objects CommentsModel
     */
    public static function getCommentsForProductList(ProductsModel $productsModel)
    {
        try {
            $commentsForProductMapper = new CommentsForProductMapper([
                'tableName'=>'comments',
                'fields'=>['id', 'date', 'text', 'name', 'id_emails', 'id_products', 'active'],
                'orderByField'=>'date',
                'orderByType'=>'DESC',
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                CommentsForProductMapper::className(),
                $commentsForProductMapper->tableName,
                implode('', $commentsForProductMapper->fields),
                $commentsForProductMapper->orderByField,
                $commentsForProductMapper->orderByType,
                $commentsForProductMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $commentsArray = $commentsForProductMapper->getGroup();
            if (!is_array($commentsArray) || empty($commentsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $commentsArray);
            return $commentsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов comments
     * @param boolean $admin флаг, указывающий выбирать данные для административного раздела
     * @return array of objects CommentsModel
     */
    public static function getCommentsList($admin=true)
    {
        try {
            $config = [
                'tableName'=>'comments',
                'fields'=>['id', 'date', 'text', 'name', 'id_emails', 'id_products', 'active'],
                'orderByField'=>'date',
                'orderByType'=>'DESC',
            ];
            if ($admin) {
                $config['queryClass'] = 'app\queries\CommentsAdminQueryCreator';
            }
            $commentsMapper = new CommentsMapper($config);
            $hash = self::createHash([
                CommentsMapper::className(), 
                $commentsMapper->tableName, 
                implode('', $commentsMapper->fields), 
                $commentsMapper->orderByField,
                $commentsMapper->orderByType,
                $commentsMapper->queryClass
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $commentsArray = $commentsMapper->getGroup();
            if (!is_array($commentsArray) || empty($commentsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $commentsArray);
            return $commentsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает CommentsModel по id
     * @param object $commentsModel экземпляр CommentsModel
     * @return object CommentsModel
     */
    public static function getCommentsById(CommentsModel $commentsModel)
    {
        try {
            $commentsByIdMapper = new CommentsByIdMapper([
                'tableName'=>'comments',
                'fields'=>['id', 'date', 'text', 'name', 'id_emails', 'id_products', 'active'],
                'model'=>$commentsModel,
            ]);
            $hash = self::createHash([
                CommentsByIdMapper::className(), 
                $commentsByIdMapper->tableName, 
                implode('', $commentsByIdMapper->fields), 
                $commentsByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $commentsModel = $commentsByIdMapper->getOneFromGroup();
            if (!is_object($commentsModel) || !$commentsModel instanceof CommentsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $commentsModel);
            return $commentsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись ProductsBrandsModel в БД, связывающую товар с брендом
     * @param object $productsModel экземпляр ProductsModel
     * @param object $brandsModel экземпляр BrandsModel
     * @return boolean
     */
    public static function setProductsBrandsInsert(ProductsModel $productsModel, BrandsModel $brandsModel)
    {
        try {
            $productsBrandsInsertMapper = new ProductsBrandsInsertMapper([
                'tableName'=>'products_brands',
                'fields'=>['id_products', 'id_brands'],
                'DbArray'=>[['id_products'=>$productsModel->id, 'id_brands'=>$brandsModel->id]],
            ]);
            if (!$result = $productsBrandsInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов ProductsBrandsModel по BrandsModel->id
     * @param object $brandsModel экземпляр BrandsModel
     * @return array ProductsModel
     */
    public static function getProductsBrandsByIdBrands(BrandsModel $brandsModel)
    {
        try {
            $productsBrandsByIdBrandsMapper = new ProductsBrandsByIdBrandsMapper([
                'tableName'=>'products_brands',
                'fields'=>['id_products', 'id_brands'],
                'model'=>$brandsModel,
            ]);
            $hash = self::createHash([
                ProductsBrandsByIdBrandsMapper::className(), 
                $productsBrandsByIdBrandsMapper->tableName, 
                implode('', $productsBrandsByIdBrandsMapper->fields), 
                $productsBrandsByIdBrandsMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $productsBrandsArray = $productsBrandsByIdBrandsMapper->getGroup();
            if (!is_array($productsBrandsArray) || empty($productsBrandsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $productsBrandsArray);
            return $productsBrandsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов ProductsColorsModel по ColorsModel->id
     * @param object $colorsModel экземпляр ColorsModel
     * @return array ProductsModel
     */
    public static function getProductsColorsByIdColors(ColorsModel $colorsModel)
    {
        try {
            $productsColorsByIdColorsMapper = new ProductsColorsByIdColorsMapper([
                'tableName'=>'products_colors',
                'fields'=>['id_products', 'id_colors'],
                'model'=>$colorsModel,
            ]);
            $hash = self::createHash([
                ProductsColorsByIdColorsMapper::className(), 
                $productsColorsByIdColorsMapper->tableName, 
                implode('', $productsColorsByIdColorsMapper->fields), 
                $productsColorsByIdColorsMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $productsColorsArray = $productsColorsByIdColorsMapper->getGroup();
            if (!is_array($productsColorsArray) || empty($productsColorsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $productsColorsArray);
            return $productsColorsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов ProductsSizesModel по SizesModel->id
     * @param object $sizesModel экземпляр SizesModel
     * @return array ProductsModel
     */
    public static function getProductsSizesByIdSizes(SizesModel $sizesModel)
    {
        try {
            $productsSizesByIdSizesMapper = new ProductsSizesByIdSizesMapper([
                'tableName'=>'products_sizes',
                'fields'=>['id_products', 'id_sizes'],
                'model'=>$sizesModel,
            ]);
            $hash = self::createHash([
                ProductsSizesByIdSizesMapper::className(), 
                $productsSizesByIdSizesMapper->tableName, 
                implode('', $productsSizesByIdSizesMapper->fields), 
                $productsSizesByIdSizesMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $productsSizesArray = $productsSizesByIdSizesMapper->getGroup();
            if (!is_array($productsSizesArray) || empty($productsSizesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $productsSizesArray);
            return $productsSizesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи ProductsBrandsModel из БД
     * @param array $productsBrandsModelsArray массив ProductsBrandsModel
     * @return int
     */
    public static function setProductsBrandsDelete(Array $productsBrandsModelsArray)
    {
        try {
            if (empty($productsBrandsModelsArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$productsBrandsModelsArray[0] instanceof ProductsBrandsModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $productsBrandsDeleteMapper = new ProductsBrandsDeleteMapper([
                'tableName'=>'products_brands',
                'objectsArray'=>$productsBrandsModelsArray,
            ]);
            if (!$result = $productsBrandsDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись ProductsColorsModel в БД, связывающую товар с colors
     * @param object $productsModel экземпляр ProductsModel
     * @param object $colorsModel экземпляр ColorsModel
     * @return boolean
     */
    public static function setProductsColorsInsert(ProductsModel $productsModel, ColorsModel $colorsModel)
    {
        try {
            if (!is_array($colorsModel->idArray) || empty($colorsModel->idArray)) {
                throw new ErrorException('Отсутствуют данные для выполнения запроса!');
            }
            $arrayToDb = [];
            foreach ($colorsModel->idArray as $colorId) {
                $arrayToDb[] = ['id_products'=>$productsModel->id, 'id_colors'=>$colorId];
            }
            $productsColorsInsertMapper = new ProductsColorsInsertMapper([
                'tableName'=>'products_colors',
                'fields'=>['id_products', 'id_colors'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $productsColorsInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи ProductsColorsModel из БД
     * @param array $productsColorsModelsArray массив ProductsColorsModel
     * @return int
     */
    public static function setProductsColorsDelete(Array $productsColorsModelsArray)
    {
        try {
            if (empty($productsColorsModelsArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$productsColorsModelsArray[0] instanceof ProductsColorsModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $productsColorsDeleteMapper = new ProductsColorsDeleteMapper([
                'tableName'=>'products_colors',
                'objectsArray'=>$productsColorsModelsArray,
            ]);
            if (!$result = $productsColorsDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
     /**
     * Создает новую запись ProductsSizesModel в БД, связывающую товар с colors
     * @param object $productsModel экземпляр ProductsModel
     * @param object $sizesModel экземпляр SizesModel
     * @return boolean
     */
    public static function setProductsSizesInsert(ProductsModel $productsModel, SizesModel $sizesModel)
    {
        try {
            if (!is_array($sizesModel->idArray) || empty($sizesModel->idArray)) {
                throw new ErrorException('Отсутствуют данные для выполнения запроса!');
            }
            $arrayToDb = [];
            foreach ($sizesModel->idArray as $sizeId) {
                $arrayToDb[] = ['id_products'=>$productsModel->id, 'id_sizes'=>$sizeId];
            }
            $productsSizesInsertMapper = new ProductsSizesInsertMapper([
                'tableName'=>'products_sizes',
                'fields'=>['id_products', 'id_sizes'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $productsSizesInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи ProductsSizesModel из БД
     * @param array $productsSizesModelsArray массив ProductsSizesModel
     * @return int
     */
    public static function setProductsSizesDelete(Array $productsSizesModelsArray)
    {
        try {
            if (empty($productsSizesModelsArray)) {
                throw new ErrorException('Неверный формат данных!');
            }
            if (!$productsSizesModelsArray[0] instanceof ProductsSizesModel) {
                throw new ErrorException('Неверный тип данных!');
            }
            $productsSizesDeleteMapper = new ProductsSizesDeleteMapper([
                'tableName'=>'products_sizes',
                'objectsArray'=>$productsSizesModelsArray,
            ]);
            if (!$result = $productsSizesDeleteMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов MailingListModel
     * @return array of objects MailingListModel
     */
    public static function getMailingList()
    {
        try {
            $mailingListMapper = new MailingListMapper([
                'tableName'=>'mailing_list',
                'fields'=>['id', 'name', 'description'],
                'orderByField'=>'name',
            ]);
            $hash = self::createHash([
                MailingListMapper::className(), 
                $mailingListMapper->tableName, 
                implode('', $mailingListMapper->fields), 
                $mailingListMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $mailingListArray = $mailingListMapper->getGroup();
            if (!is_array($mailingListArray) || empty($mailingListArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $mailingListArray);
            return $mailingListArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись EmailsMailingListModel в БД, связывающую email с рассылками
     * @param object $emailsModel экземпляр EmailsModel
     * @param object $mailingListModel экземпляр MailingListModel
     * @return boolean
     */
    public static function setEmailsMailingListInsert(EmailsModel $emailsModel, MailingListModel $mailingListModel)
    {
        try {
            if (!is_array($mailingListModel->idFromForm) || empty($mailingListModel->idFromForm)) {
                throw new ErrorException('Отсутствуют данные для выполнения запроса!');
            }
            $arrayToDb = [];
            foreach ($mailingListModel->idFromForm as $mailingListId) {
                $arrayToDb[] = ['id_email'=>$emailsModel->id, 'id_mailing_list'=>$mailingListId];
            }
            $emailsMailingListInsertMapper = new EmailsMailingListInsertMapper([
                'tableName'=>'emails_mailing_list',
                'fields'=>['id_email', 'id_mailing_list'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $emailsMailingListInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает MailingListModel по id
     * @param object $mailingListModel экземпляр MailingListModel
     * @return object MailingListModel
     */
    public static function getMailingListById(MailingListModel $mailingListModel)
    {
        try {
            $mailingListByIdMapper = new MailingListByIdMapper([
                'tableName'=>'mailing_list',
                'fields'=>['id', 'name', 'description'],
                'model'=>$mailingListModel,
            ]);
            $hash = self::createHash([
                MailingListByIdMapper::className(), 
                $mailingListByIdMapper->tableName, 
                implode('', $mailingListByIdMapper->fields), 
                $mailingListByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $mailingListModel = $mailingListByIdMapper->getOneFromGroup();
            if (!is_object($mailingListModel) || !$mailingListModel instanceof MailingListModel) {
                return null;
            }
            self::createRegistryEntry($hash, $mailingListModel);
            return $mailingListModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов MailingListModel для текущего EmailsModel по email
     * @param object $emailsModel экземпляр EmailsModel
     * @return array of objects MailingListModel
     */
    public static function getMailingListForEmail(EmailsModel $emailsModel)
    {
        try {
            $mailingListForEmailMapper = new MailingListForEmailMapper([
                'tableName'=>'mailing_list',
                'fields'=>['id', 'name', 'description'],
                'orderByField'=>'name',
                'model'=>$emailsModel,
            ]);
            $hash = self::createHash([
                MailingListForEmailMapper::className(), 
                $mailingListForEmailMapper->tableName, 
                implode('', $mailingListForEmailMapper->fields), 
                $mailingListForEmailMapper->orderByField,
                $mailingListForEmailMapper->model->email,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $mailingList = $mailingListForEmailMapper->getGroup();
            if (!is_array($mailingList) || empty($mailingList)) {
                return null;
            }
            self::createRegistryEntry($hash, $mailingList);
            return $mailingList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет записи EmailsMailingListModel из БД
     * @param array массив объектов EmailsMailingListModel
     * @return int
     */
    public static function setEmailsMailingListDelete(Array $emailsMailingListModelArray)
    {
        try {
            $emailsMailingListDeleteMapper = new EmailsMailingListDeleteMapper([
                'tableName'=>'emails_mailing_list',
                'fields'=>['id_email', 'id_mailing_list'],
                'objectsArray'=>$emailsMailingListModelArray,
            ]);
            $result = $emailsMailingListDeleteMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов AdminMenuModel
     * @return array
     */
    public static function getAdminMenuList()
    {
        try {
            $adminMenuMapper = new AdminMenuMapper([
                'tableName'=>'admin_menu',
                'fields'=>['id', 'name', 'route'],
                'orderByField'=>'name',
            ]);
            $hash = self::createHash([
                AdminMenuMapper::className(), 
                $adminMenuMapper->tableName, 
                implode('', $adminMenuMapper->fields), 
                $adminMenuMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectsRegistry[$hash];
            }
            $adminMenuArray = $adminMenuMapper->getGroup();
            if (!is_array($adminMenuArray) || empty($adminMenuArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $adminMenuArray);
            return $adminMenuArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обнуляет значение всех свойств класса
     * @return boolean
     */
    public static function cleanProperties()
    {
        try {
            self::$_objectsRegistry = array();
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Сравнивает хеш текущего объекта с хешами-ключами в MappersHelper::$_objectsRegistry, 
     * возвращает true, если совпадение найдено, иначе false
     * @params string хеш
     * @return boolean
     */
    private static function compareHashes(string $hash)
    {
        try {
            if (!array_key_exists($hash, self::$_objectsRegistry)) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Сохраняет загруженные данные в реестре MappersHelper::$_objectsRegistry
     * @param string $hash хеш сохраняемого объекта, который станет ключом в реестре
     * @param object $object объект, который необходимо сохранить в реестре
     * @return boolean
     */
    private static function createRegistryEntry(string $hash, $object)
    {
        try {
            self::$_objectsRegistry[$hash] = $object;
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует хеш с помощью функции md5
     * @param array $inputArray массив данных для конструирования хеша
     * @return string результирующий хеш
     */
    private static function createHash(Array $inputArray)
    {
        try {
            return HashHelper::createHash($inputArray);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив реестра MappersHelper::$_objectsRegistry
     * @return array
     */
    public static function getObjectRegistry()
    {
        try {
            return self::$_objectsRegistry;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
