<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\mappers\ColorsMapper;
use app\mappers\SizesMapper;
use app\mappers\BrandsMapper;
use app\mappers\CurrencyMapper;
use app\mappers\CategoriesMapper;
use app\mappers\EmailsByEmailMapper;
use app\mappers\EmailsInsertMapper;
use app\mappers\AddressByAddressMapper;
use app\mappers\AddressInsertMapper;
use app\mappers\DeliveriesByIdMapper;
use app\mappers\PaymentsByIdMapper;
use app\mappers\PhonesByPhoneMapper;
use app\mappers\PhonesInsertMapper;
use app\mappers\UsersUpdateMapper;
use app\mappers\UsersInsertMapper;
use app\mappers\UsersPurchasesInsertMapper;
use app\mappers\UsersRulesInsertMapper;
use app\models\AddressModel;
use app\models\EmailsModel;
use app\models\PaymentsModel;
use app\models\PhonesModel;
use app\models\UsersModel;
use app\models\DeliveriesModel;

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
     * @var object объект AddressModel
     * @see MappersHelper::getAddressModel()
     */
    private static $_addressModel = NULL;
    /**
     * @var object объект PhonesModel
     * @see MappersHelper::getPhonesModel()
     */
    private static $_phonesModel = NULL;
    /**
     * @var object объект DeliveriesModel
     * @see MappersHelper::getDeliveriesModel()
     */
    private static $_deliveriesModel = NULL;
    /**
     * @var object объект PaymentsModel
     * @see MappersHelper::getPaymentsModel()
     */
    private static $_paymentsModel = NULL;
    /**
     * @var object объект EmailsModel
     * @see MappersHelper::getEmailsModel()
     */
    private static $_emailsModel = NULL;
    
    /**
     * Получает массив объектов категорий
     * @return array of objects
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
                    throw new ErrorException('Ошибка при формировании массива категорий!');
                }
                self::$_categoriesList = $categoriesArray;
            }
            return self::$_categoriesList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов валют
     * @return array of objects
     */
    public static function getСurrencyList()
    {
        try {
            if (empty(self::$_currencyList)) {
                $currencyMapper = new CurrencyMapper([
                    'tableName'=>'currency',
                    'fields'=>['id', 'currency'],
                    'orderByField'=>'currency'
                ]);
                $currencyArray = $currencyMapper->getGroup();
                if (!is_array($currencyArray) || empty($currencyArray)) {
                    throw new ErrorException('Ошибка при формировании массива валют!');
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
     * @return array of objects
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
                    throw new ErrorException('Ошибка при формировании массива colors!');
                }
                self::$_colorsList = $colorsArray;
            }
            return self::$_colorsList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов sizes
     * @return array of objects
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
                    throw new ErrorException('Ошибка при формировании массива sizes!');
                }
                self::$_sizesList = $sizesArray;
            }
            return self::$_sizesList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов brands
     * @return array of objects
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
                    throw new ErrorException('Ошибка при формировании массива sizes!');
                }
                self::$_brandsList = $brandsArray;
            }
            return self::$_brandsList;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существет ли запись в БД для address, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $addressModel экземпляр AddressModel
     * @return object
     */
    public static function getAddressModel(AddressModel $addressModel)
    {
        try {
            if (empty(self::$_addressModel)) {
                self::$_addressModel = $addressModel;
                $addressByAddressMapper = new AddressByAddressMapper([
                    'tableName'=>'address',
                    'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                    'model'=>self::$_addressModel
                ]);
                $result = $addressByAddressMapper->getOneFromGroup();
                if (is_object($result) && $result instanceof AddressModel) {
                    self::$_addressModel = $result;
                } else {
                    $addressInsertMapper = new AddressInsertMapper([
                        'tableName'=>'address',
                        'fields'=>['address', 'city', 'country', 'postcode'],
                        'objectsArray'=>[self::$_addressModel],
                    ]);
                    if (!$addressInsertMapper->setGroup()) {
                        throw new ErrorException('Не удалось сохранить данные AddressModel!');
                    }
                }
            }
            return self::$_addressModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существет ли запись в БД для phones, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $phonesModel экземпляр PhonesModel
     * @return object
     */
    public static function getPhonesModel(PhonesModel $phonesModel)
    {
        try {
            if (empty(self::$_phonesModel)) {
                self::$_phonesModel = $phonesModel;
                $phonesByPhoneMapper = new PhonesByPhoneMapper([
                    'tableName'=>'phones',
                    'fields'=>['id', 'phone'],
                    'model'=>self::$_phonesModel
                ]);
                $result = $phonesByPhoneMapper->getOneFromGroup();
                if (is_object($result) && $result instanceof PhonesModel) {
                    self::$_phonesModel = $result;
                } else {
                    $phonesInsertMapper = new PhonesInsertMapper([
                        'tableName'=>'phones',
                        'fields'=>['phone'],
                        'objectsArray'=>[self::$_phonesModel],
                    ]);
                    if (!$phonesInsertMapper->setGroup()) {
                        throw new ErrorException('Не удалось сохранить данные PhonesModel!');
                    }
                }
            }
            return self::$_phonesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
      * Получает DeliveriesModel по id
     * @param object $deliveriesModel экземпляр DeliveriesModel
     * @return object
     */
    public static function getDeliveriesModel(DeliveriesModel $deliveriesModel)
    {
        try {
            if (empty(self::$_deliveriesModel)) {
                $deliveriesByIdMapper = new DeliveriesByIdMapper([
                    'tableName'=>'deliveries',
                    'fields'=>['id', 'name', 'description', 'price'],
                    'model'=>$deliveriesModel,
                ]);
                $result = $deliveriesByIdMapper->getOneFromGroup();
                if (!is_object($result) || !$result instanceof DeliveriesModel) {
                    throw new ErrorException('Ошибка при получении данных DeliveriesModel из БД!');
                }
                self::$_deliveriesModel = $result;
            }
            return self::$_deliveriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает PaymentsModel по id
     * @param object $paymentsModel экземпляр PaymentsModel
     * @return object
     */
    public static function getPaymentsModel(PaymentsModel $paymentsModel)
    {
        try {
            if (empty(self::$_paymentsModel)) {
                $paymentsByIdMapper = new PaymentsByIdMapper([
                    'tableName'=>'payments',
                    'fields'=>['id', 'name', 'description'],
                    'model'=>$paymentsModel,
                ]);
                $result = $paymentsByIdMapper->getOneFromGroup();
                if (!is_object($result) || !$result instanceof PaymentsModel) {
                    throw new ErrorException('Ошибка при получении данных PaymentsModel из БД!');
                }
                self::$_paymentsModel = $result;
            }
            return self::$_paymentsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись UsersPurchases в БД, вязывающую пользователя с купленным товаром
     * @return boolean
     */
    public static function setUsersPurchases()
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
                throw new ErrorException('Не удалось сохранить данные в БД!');
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Проверяет, авторизирован ли user в системе, если да, обновляет данные,
     * если нет, создает новую запись в БД
     * @param object $usersModel экземпляр UsersModel
     * @return int
     */
    public static function setOrUpdateUsers(UsersModel $usersModel)
    {
        try {
            if (\Yii::$app->user->login != \Yii::$app->params['nonAuthenticatedUserLogin'] && !empty(\Yii::$app->user->id)) {
                \Yii::configure($usersModel, ['id'=>\Yii::$app->user->id]);
                if (!empty(array_diff_assoc($usersModel->getDataForСomparison(), \Yii::$app->user->getDataForСomparison()))) {
                    $usersUpdateMapper = new UsersUpdateMapper([
                        'tableName'=>'users',
                        'fields'=>['name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                        'model'=>$usersModel,
                    ]);
                    if (!$result = $usersUpdateMapper->setGroup()) {
                        throw new ErrorException('Не удалось обновить данные UsersModel в БД!');
                    }
                    if (!UserAuthenticationHelper::fill($usersModel)) {
                        throw new ErrorException('Ошибка при обновлении данных \Yii::$app->user!');
                    }
                }
            } else {
                $usersInsertMapper = new UsersInsertMapper([
                    'tableName'=>'users',
                    'fields'=>['login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                    'objectsArray'=>[$usersModel],
                ]);
                if (!$usersInsertMapper->setGroup()) {
                    throw new ErrorException('Не удалось добавить данные UsersModel в БД!');
                }
                if (!self::setUsersRules($usersModel)) {
                    throw new ErrorException('Ошибка при сохранении связи пользователя с правами доступа!');
                }
            }
            return $usersModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Проверяет, существет ли запись в БД для такого email, если да, возвращает ее,
     * если нет, создает новую запись в БД
     * @param object $emailsModel экземпляр EmailsModel
     * @return object
     */
    public static function getEmailsModel(EmailsModel $emailsModel)
    {
        try {
            if (empty(self::$_emailsModel)) {
                self::$_emailsModel = $emailsModel;
                $emailsByEmailMapper = new EmailsByEmailMapper([
                    'tableName'=>'emails',
                    'fields'=>['id', 'email'],
                    'model'=>self::$_emailsModel
                ]);
                $result = $emailsByEmailMapper->getOneFromGroup();
                if (is_object($result) && $result instanceof EmailsModel) {
                    self::$_emailsModel = $result;
                } else {
                    $emailsInsertMapper = new EmailsInsertMapper([
                        'tableName'=>'emails',
                        'fields'=>['email'],
                        'objectsArray'=>[self::$_emailsModel],
                    ]);
                    if (!$emailsInsertMapper->setGroup()) {
                        throw new ErrorException('Не удалось обновить данные в БД!');
                    }
                }
            }
            return self::$_emailsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись в БД, связывающую пользователя с правами доступа
     * @param object $usersModel экземпляр UsersModel
     * @return int
     */
    public static function setUsersRules(UsersModel $usersModel)
    {
        try {
            $usersRulesInsertMapper = new UsersRulesInsertMapper([
                'tableName'=>'users_rules',
                'fields'=>['id_users', 'id_rules'],
                'model'=>$usersModel
            ]);
            if (!$result = $usersRulesInsertMapper->setGroup()) {
                throw new ErrorException('Не удалось добавить данные UsersRules в БД!');
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
