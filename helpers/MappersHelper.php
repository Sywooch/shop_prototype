<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\EmptyListException;
use app\traits\ExceptionsTrait;
use app\mappers\ColorsMapper;
use app\mappers\SizesMapper;
use app\mappers\BrandsMapper;
use app\mappers\CurrencyMapper;
use app\mappers\CategoriesMapper;
use app\mappers\EmailsByEmailMapper;
use app\mappers\EmailsInsertMapper;
use app\mappers\AddressByAddressMapper;
use app\mappers\AddressByIdMapper;
use app\mappers\AddressInsertMapper;
use app\mappers\DeliveriesByIdMapper;
use app\mappers\PaymentsByIdMapper;
use app\mappers\PhonesByPhoneMapper;
use app\mappers\PhonesInsertMapper;
use app\mappers\UsersUpdateMapper;
use app\mappers\UsersInsertMapper;
use app\mappers\UsersPurchasesInsertMapper;
use app\mappers\UsersRulesInsertMapper;
use app\mappers\CurrencyByIdMapper;
use app\mappers\CommentsInsertMapper;
use app\mappers\ProductDetailMapper;
use app\mappers\ProductsListMapper;
use app\mappers\UsersByLoginMapper;
use app\mappers\DeliveriesMapper;
use app\mappers\RulesMapper;
use app\mappers\EmailsByIdMapper;
use app\mappers\PhonesByIdMapper;
use app\mappers\CurrencyByMainMapper;
use app\mappers\SubcategoryForCategoryMapper;
use app\mappers\ColorsForProductMapper;
use app\mappers\SizesForProductMapper;
use app\mappers\SimilarProductsMapper;
use app\mappers\RelatedProductsMapper;
use app\mappers\CommentsForProductMapper;
use app\mappers\PaymentsMapper;
use app\mappers\ProductsByCodeMapper;
use app\mappers\CategoriesByIdMapper;
use app\mappers\SubcategoryByIdMapper;
use app\mappers\ProductsBrandsInsertMapper;
use app\models\AddressModel;
use app\models\EmailsModel;
use app\models\PaymentsModel;
use app\models\PhonesModel;
use app\models\UsersModel;
use app\models\DeliveriesModel;
use app\models\CurrencyModel;
use app\models\CommentsModel;
use app\models\ProductsModel;
use app\models\CategoriesModel;
use app\models\SubcategoryModel;
use app\models\BrandsModel;

/**
 * Коллекция методов, которые взаимодействуют с БД посредством мапперов
 */
class MappersHelper
{
    use ExceptionsTrait;
    
    /**
     * @var array массив объектов категорий
     * @see MappersHelper::getCategoriesList()
     */
    private static $_categoriesList = array();
    /**
     * @var array массив объектов валют
     * @see MappersHelper::getСurrencyList()
     */
    private static $_currencyList = array();
    /**
     * @var array массив объектов colors
     * @see MappersHelper::getColorsList()
     */
    private static $_colorsList = array();
    /**
     * @var array массив объектов sizes
     * @see MappersHelper::getSizesList()
     */
    private static $_sizesList = array();
    /**
     * @var array массив объектов brands
     * @see MappersHelper::getBrandsList()
     */
    private static $_brandsList = array();
    /**
     * @var array массив объектов deliveries
     * @see MappersHelper::getDeliveriesList()
     */
    private static $_deliveriesList = array();
    /**
     * @var array массив объектов rules
     * @see MappersHelper::getRulesList()
     */
    private static $_rulesList = array();
    /**
     * @var array массив объектов payments
     * @see MappersHelper::getPaymentsList()
     */
    private static $_paymentsList = array();
    
    /**
     * Получает массив объектов категорий
     * @return array of objects CategoriesModel
     */
    public static function getCategoriesList()
    {
        try {
            if (empty(self::$_categoriesList)) {
                $categoriesMapper = new CategoriesMapper([
                    'tableName'=>'categories',
                    'fields'=>['id', 'name', 'seocode'],
                    'orderByField'=>'name'
                ]);
                $categoriesArray = $categoriesMapper->getGroup();
                if (!is_array($categoriesArray) || empty($categoriesArray)) {
                    return NULL;
                }
                self::$_categoriesList = $categoriesArray;
            }
            return self::$_categoriesList;
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
            $categoriesModel = $categoriesByIdMapper->getOneFromGroup();
            if (!is_object($categoriesModel) && !$categoriesModel instanceof CategoriesModel) {
                return NULL;
            }
            return $categoriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов валют
     * @return array of objects CurrencyModel
     */
    public static function getСurrencyList()
    {
        try {
            if (empty(self::$_currencyList)) {
                $currencyMapper = new CurrencyMapper([
                    'tableName'=>'currency',
                    'fields'=>['id', 'currency', 'exchange_rate', 'main'],
                    'orderByField'=>'currency'
                ]);
                $currencyArray = $currencyMapper->getGroup();
                if (!is_array($currencyArray) || empty($currencyArray)) {
                    return NULL;
                }
                self::$_currencyList = $currencyArray;
            }
            return self::$_currencyList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов colors
     * @return array of objects ColorsModel
     */
    public static function getColorsList()
    {
        try {
            if (empty(self::$_colorsList)) {
                $colorsMapper = new ColorsMapper([
                    'tableName'=>'colors',
                    'fields'=>['id', 'color'],
                    'orderByField'=>'color',
                ]);
                $colorsArray = $colorsMapper->getGroup();
                if (!is_array($colorsArray) || empty($colorsArray)) {
                    return NULL;
                }
                self::$_colorsList = $colorsArray;
            }
            return self::$_colorsList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов colors для текущего products
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
            $colorsArray = $colorsMapper->getGroup();
            if (!is_array($colorsArray) || empty($colorsArray)) {
                return NULL;
            }
            return $colorsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов sizes
     * @return array of objects SizesModel
     */
    public static function getSizesList()
    {
        try {
            if (empty(self::$_sizesList)) {
                $sizesMapper = new SizesMapper([
                    'tableName'=>'sizes',
                    'fields'=>['id', 'size'],
                    'orderByField'=>'size'
                ]);
                $sizesArray = $sizesMapper->getGroup();
                if (!is_array($sizesArray) || empty($sizesArray)) {
                    return NULL;
                }
                self::$_sizesList = $sizesArray;
            }
            return self::$_sizesList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов sizes для текущего products
     * @param object $productsModel экземпляр ProductsModel
     * @return array of objects SizesModel
     */
    public static function getSizesForProductList(ProductsModel $productsModel)
    {
        try {
            $sizesMapper = new SizesForProductMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size',
                'model'=>$productsModel,
            ]);
            $sizesArray = $sizesMapper->getGroup();
            if (!is_array($sizesArray) || empty($sizesArray)) {
                return NULL;
            }
            return $sizesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов brands
     * @return array of objects BrandsModel
     */
    public static function getBrandsList()
    {
        try {
            if (empty(self::$_brandsList)) {
                $brandsMapper = new BrandsMapper([
                    'tableName'=>'brands',
                    'fields'=>['id', 'brand'],
                    'orderByField'=>'brand'
                ]);
                $brandsArray = $brandsMapper->getGroup();
                if (!is_array($brandsArray) || empty($brandsArray)) {
                    return NULL;
                }
                self::$_brandsList = $brandsArray;
            }
            return self::$_brandsList;
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
            $addressModel = $addressByAddressMapper->getOneFromGroup();
            if (!is_object($addressModel) && !$addressModel instanceof AddressModel) {
                return NULL;
            }
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
                return NULL;
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
            $addressModel = $addressByIdMapper->getOneFromGroup();
            if (!is_object($addressModel) || !$addressModel instanceof AddressModel) {
                return NULL;
            }
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
            $phonesModel = $phonesByPhoneMapper->getOneFromGroup();
            if (!is_object($phonesModel) && !$phonesModel instanceof PhonesModel) {
                return NULL;
            }
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
                return NULL;
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
            $phonesModel = $phonesByIdMapper->getOneFromGroup();
            if (!is_object($phonesModel) || !$phonesModel instanceof PhonesModel) {
                return NULL;
            }
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
            $deliveriesModel = $deliveriesByIdMapper->getOneFromGroup();
            if (!is_object($deliveriesModel) || !$deliveriesModel instanceof DeliveriesModel) {
                return NULL;
            }
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
            if (empty(self::$_deliveriesList)) {
                $deliveriesMapper = new DeliveriesMapper([
                    'tableName'=>'deliveries',
                    'fields'=>['id', 'name', 'description', 'price'],
                    'orderByField'=>'id'
                ]);
                $deliveriesArray = $deliveriesMapper->getGroup();
                if (!is_array($deliveriesArray) || empty($deliveriesArray)) {
                    return NULL;
                }
                self::$_deliveriesList = $deliveriesArray;
            }
            return self::$_deliveriesList;
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
            $paymentsModel = $paymentsByIdMapper->getOneFromGroup();
            if (!is_object($paymentsModel) || !$paymentsModel instanceof PaymentsModel) {
                return NULL;
            }
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
            if (empty(self::$_paymentsList)) {
                $paymentsMapper = new PaymentsMapper([
                    'tableName'=>'payments',
                    'fields'=>['id', 'name', 'description'],
                ]);
                $paymentsArray = $paymentsMapper->getGroup();
                if (!is_array($paymentsArray) || empty($paymentsArray)) {
                    return NULL;
                }
                self::$_paymentsList = $paymentsArray;
            }
            return self::$_paymentsList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись UsersPurchases в БД, вязывающую пользователя с купленным товаром
     * @return boolean
     */
    public static function setUsersPurchasesInsert()
    {
        try {
            $id_users = \Yii::$app->cart->user->id;
            $productsArray = \Yii::$app->cart->getProductsArray();
            $id_deliveries = \Yii::$app->cart->user->deliveries->id;
            $id_payments = \Yii::$app->cart->user->payments->id;
            
            if (empty($id_users)) {
                throw new ErrorException('Отсутствует cart->user->id!');
            }
            if (!is_array($productsArray) || empty($productsArray)) {
                throw new ErrorException('Отсутствуют данные в массиве cart->productsArray!');
            }
            if (empty($id_deliveries)) {
                throw new ErrorException('Отсутствует user->deliveries->id!');
            }
            if (empty($id_payments)) {
                throw new ErrorException('Отсутствует user->payments->id!');
            }
            
            $arrayToDb = [];
            foreach ($productsArray as $product) {
                $arrayToDb[] = ['id_users'=>$id_users, 'id_products'=>$product->id, 'quantity'=>$product->quantity, 'id_colors'=>$product->colorToCart, 'id_sizes'=>$product->sizeToCart, 'id_deliveries'=>$id_deliveries, 'id_payments'=>$id_payments];
            }
            
            $usersPurchasesInsertMapper = new UsersPurchasesInsertMapper([
                'tableName'=>'users_purchases',
                'fields'=>['id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $usersPurchasesInsertMapper->setGroup()) {
                return NULL;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет запись в БД объектом UsersModel
     * @return objects UsersModel
     */
    public static function setUsersUpdate(UsersModel $usersModel)
    {
        try {
            $usersUpdateMapper = new UsersUpdateMapper([
                'tableName'=>'users',
                'fields'=>['name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                'model'=>$usersModel,
            ]);
            $result = $usersUpdateMapper->setGroup();
            if (!$result) {
                return NULL;
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
                'fields'=>['login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                'objectsArray'=>[$usersModel],
            ]);
            $result = $usersInsertMapper->setGroup();
            if (!$result) {
                return NULL;
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
            $emailsModel = $emailsByEmailMapper->getOneFromGroup();
            if (!is_object($emailsModel) && !$emailsModel instanceof EmailsModel) {
                return NULL;
            }
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
                return NULL;
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
            $emailsModel = $emailsByIdMapper->getOneFromGroup();
            if (!is_object($emailsModel) || !$emailsModel instanceof EmailsModel) {
                return NULL;
            }
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
                return NULL;
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
            $currencyModel = $currencyByIdMapper->getOneFromGroup();
            if (!is_object($currencyModel) && !$currencyModel instanceof CurrencyModel) {
                return NULL;
            }
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
            $currencyModel = $currencyByMainMapper->getOneFromGroup();
            if (!is_object($currencyModel) || !$currencyModel instanceof CurrencyModel) {
                return NULL;
            }
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
                'fields'=>['text', 'name', 'id_emails', 'id_products'],
                'objectsArray'=>[$commentsModel],
            ]);
            if (!$result = $commentsInsertMapper->setGroup()) {
                return NULL;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект ProductsModel по id, взятому из $_GET
     * @return objects ProductsModel
     */
    public static function getProductDetail()
    {
        try {
            $productMapper = new ProductDetailMapper([
                'tableName'=>'products',
                'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            ]);
            $productsObject = $productMapper->getOneFromGroup();
            if (!is_object($productsObject) || !$productsObject instanceof ProductsModel) {
                return NULL;
            }
            return $productsObject;
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
            $productsArray = $productsMapper->getGroup();
            if (!is_array($productsArray) || empty($productsArray) || !$productsArray[0] instanceof ProductsModel) {
                return NULL;
            }
            return $productsArray;
        } catch (EmptyListException $e) {
            throw $e;
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
            $productsModel = $productsByCodeMapper->getOneFromGroup();
            if (!is_object($productsModel) && !$productsModel instanceof ProductsModel) {
                return NULL;
            }
            return $productsModel;
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
            $usersInsertMapper = new UsersInsertMapper([
                'tableName'=>'products',
                'fields'=>['date', 'code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'],
                'objectsArray'=>[$productsModel],
            ]);
            $result = $usersInsertMapper->setGroup();
            if (!$result) {
                return NULL;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект UsersModel по login
     * @param object $usersModel экземпляр UsersModel
     * @return objects UsersModel
     */
    public static function getUsersByLogin(UsersModel $usersModel)
    {
        try {
            $usersByLoginMapper = new UsersByLoginMapper([
                'tableName'=>'users',
                'fields'=>['id', 'login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                'model'=>$usersModel
            ]);
            $usersModel = $usersByLoginMapper->getOneFromGroup();
            if (!is_object($usersModel) || !$usersModel instanceof UsersModel) {
                return NULL;
            }
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
            if (empty(self::$_rulesList)) {
                $rulesMapper = new RulesMapper([
                    'tableName'=>'rules',
                    'fields'=>['id', 'rule'],
                    'orderByField'=>'rule',
                ]);
                $rulesArray = $rulesMapper->getGroup();
                if (!is_array($rulesArray) || empty($rulesArray)) {
                    return NULL;
                }
                self::$_rulesList = $rulesArray;
            }
            return self::$_rulesList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов subcategory
     * @return array of objects SubcategoryModel
     */
    public static function getSubcategoryForCategoryList(CategoriesModel $categoriesModel)
    {
        try {
            $subcategoryMapper = new SubcategoryForCategoryMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'model'=>$categoriesModel
            ]);
            $subcategoryArray = $subcategoryMapper->getGroup();
            if (!is_array($subcategoryArray) || empty($subcategoryArray)) {
                return NULL;
            }
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
            $subcategoryModel = $subcategoryByIdMapper->getOneFromGroup();
            if (!is_object($subcategoryModel) && !$subcategoryModel instanceof SubcategoryModel) {
                return NULL;
            }
            return $subcategoryModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов products, похожих свойствами с текущим products
     * @return array of objects ProductsModel
     */
    public static function getSimilarProductsList(ProductsModel $productsModel)
    {
        try {
            $similarProductsMapper = new SimilarProductsMapper([
                'tableName'=>'products',
                'fields'=>['id', 'name', 'price', 'images'],
                'orderByField'=>'date',
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'model'=>$productsModel,
            ]);
            $similarsArray = $similarProductsMapper->getGroup();
            if (!is_array($similarsArray) || empty($similarsArray)) {
                return NULL;
            }
            return $similarsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов products, связанных с текущим products
     * @return array of objects ProductsModel
     */
    public static function getRelatedProductsList(ProductsModel $productsModel)
    {
        try {
            $relatedProductsMapper = new RelatedProductsMapper([
                'tableName'=>'products',
                'fields'=>['id', 'name', 'price', 'images'],
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'orderByField'=>'date',
                'model'=>$productsModel,
            ]);
            $relatedArray = $relatedProductsMapper->getGroup();
            if (!is_array($relatedArray) || empty($relatedArray)) {
                return NULL;
            }
            return $relatedArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов comments для текущего products
     * @param object $productsModel экземпляр ProductsModel
     * @return array of objects CommentsModel
     */
    public static function getCommentsForProductList(ProductsModel $productsModel)
    {
        try {
            $commentsForProductMapper = new CommentsForProductMapper([
                'tableName'=>'comments',
                'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
                'model'=>$productsModel,
            ]);
            $commentsArray = $commentsForProductMapper->getGroup();
            if (!is_array($commentsArray) || empty($commentsArray)) {
                return NULL;
            }
            return $commentsArray;
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
                return NULL;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
