<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;
use app\models\CurrencyModel;
use app\models\ColorsModel;
use app\models\SizesModel;
use app\models\BrandsModel;
use app\models\AddressModel;
use app\models\PhonesModel;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;
use app\models\ProductsModel;
use app\models\UsersModel;
use app\models\EmailsModel;
use app\models\CommentsModel;

/**
 * Тестирует класс app\helpers\MappersHelper
 */
class MappersHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_images = 'images';
    private static $_name = 'Some Name';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_color = 'gray';
    private static $_code = 'Fghr8';
    private static $_size = '46';
    private static $_brand = 'Some Brand';
    private static $_address = 'Some address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = '06589';
    private static $_phone = '+396548971203';
    private static $_description = 'Some description';
    private static $_price = 12.34;
    private static $_login = 'Somelogin';
    private static $_password = 'iJ7gdJ';
    private static $_surname = 'Some Surname';
    private static $_quantity = 1;
    private static $_rule = 'rule';
    private static $_email = 'some@some.com';
    private static $_currency = 'EUR';
    private static $_exchange_rate = '12.456';
    private static $_main = '1';
    private static $_text = 'Some Text';
    
    private static $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'orderByField'=>'date',
        'getDataSorting'=>false,
    ];
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('app\helpers\MappersHelper');
    }
    
    /**
     * Тестирует наличие свойств и методов в классе app\helpers\MappersHelper
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasProperty('_categoriesList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_currencyList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_colorsList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_sizesList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_brandsList'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_addressModel'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_phonesModel'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_deliveriesModel'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_paymentsModel'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('_emailsModel'));
        
        $this->assertTrue(self::$_reflectionClass->hasMethod('getCategoriesList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getСurrencyList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getColorsList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getSizesList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getBrandsList'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getAddressModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getPhonesModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getDeliveriesModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getPaymentsModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('setUsersPurchases'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('setOrUpdateUsers'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('getEmailsModel'));
        $this->assertTrue(self::$_reflectionClass->hasMethod('setUsersRules'));
    }
    
    /**
     * Тестирует метод MappersHelper::getCategoriesList
     */
    public function testGetCategoriesList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $result = MappersHelper::getCategoriesList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof CategoriesModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_name, $result[0]->name);
        $this->assertEquals(self::$_categorySeocode, $result[0]->seocode);
    }
    
    /**
     * Тестирует метод MappersHelper::getСurrencyList
     */
    public function testGetСurrencyList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency, [[exchange_rate]]=:exchange_rate, [[main]]=:main');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency, ':exchange_rate'=>self::$_exchange_rate, ':main'=>self::$_main]);
        $command->execute();
        
        $result = MappersHelper::getСurrencyList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof CurrencyModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_currency, $result[0]->currency);
        $this->assertEquals(self::$_exchange_rate, $result[0]->exchange_rate);
        $this->assertEquals(self::$_main, $result[0]->main);
    }
    
    /**
     * Тестирует метод MappersHelper::getColorsList
     */
    public function testGetColorsList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $result = MappersHelper::getColorsList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof ColorsModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_color, $result[0]->color);
    }
    
    /**
     * Тестирует метод MappersHelper::getSizesList
     */
    public function testGetSizesList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        $result = MappersHelper::getSizesList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof SizesModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_size, $result[0]->size);
    }
    
    /**
     * Тестирует метод MappersHelper::getBrandsList
     */
    public function testGetBrandsList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_brands}} SET [[id_products]]=:id_products, [[id_brands]]=:id_brands');
        $command->bindValues([':id_products'=>self::$_id, ':id_brands'=>self::$_id]);
        $command->execute();
        
        $result = MappersHelper::getBrandsList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof BrandsModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_brand, $result[0]->brand);
    }
    
    /**
     * Тестирует метод MappersHelper::getAddressModel
     * в процессе создания записи в БД
     */
    public function testGetAddressModelOne()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{address}}')->queryAll()));
        
        $addressModel = new AddressModel();
        $addressModel->address = self::$_address;
        $addressModel->city = self::$_city;
        $addressModel->country = self::$_country;
        $addressModel->postcode = self::$_postcode;
        
        MappersHelper::getAddressModel($addressModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{address}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_address, $result[0]['address']);
        $this->assertEquals(self::$_city, $result[0]['city']);
        $this->assertEquals(self::$_country, $result[0]['country']);
        $this->assertEquals(self::$_postcode, $result[0]['postcode']);
    }
    
    /**
     * Тестирует метод MappersHelper::getAddressModel
     * в процессе получения существующей записи из БД
     */
    public function testGetAddressModelTwo()
    {
        $addressModel = new AddressModel();
        $addressModel->address = self::$_address;
        $addressModel->city = self::$_city;
        $addressModel->country = self::$_country;
        $addressModel->postcode = self::$_postcode;
        
        $result = MappersHelper::getAddressModel($addressModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof AddressModel);
        $this->assertEquals(self::$_address, $result->address);
        $this->assertEquals(self::$_city, $result->city);
        $this->assertEquals(self::$_country, $result->country);
        $this->assertEquals(self::$_postcode, $result->postcode);
    }
    
    /**
     * Тестирует метод MappersHelper::getPhonesModel
     * в процессе создания записи в БД
     */
    public function testGetPhonesModelOne()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{phones}}')->queryAll()));
        
        $phonesModel = new PhonesModel();
        $phonesModel->phone = self::$_phone;
        
        MappersHelper::getPhonesModel($phonesModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{phones}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_phone, $result[0]['phone']);
    }
    
    /**
     * Тестирует метод MappersHelper::getPhonesModel
     * в процессе получения существующей записи из БД
     */
    public function testGetPhonesModelTwo()
    {
        $phonesModel = new PhonesModel();
        $phonesModel->phone = self::$_phone;
        
        $result = MappersHelper::getPhonesModel($phonesModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof PhonesModel);
        $this->assertEquals(self::$_phone, $result->phone);
    }
    
    /**
     * Тестирует метод MappersHelper::getDeliveriesModel
     */
    public function testGetDeliveriesModel()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{deliveries}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description, [[price]]=:price');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price]);
        $command->execute();
        
        $deliveriesModel = new DeliveriesModel();
        $deliveriesModel->id = self::$_id;
        
        $result = MappersHelper::getDeliveriesModel($deliveriesModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof DeliveriesModel);
        $this->assertEquals(self::$_id, $result->id);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_description, $result->description);
        $this->assertEquals(self::$_price, $result->price);
    }
    
    /**
     * Тестирует метод MappersHelper::getPaymentsModel
     */
    public function testGetPaymentsModel()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{payments}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        $paymentsModel = new PaymentsModel();
        $paymentsModel->id = self::$_id;
        
        $result = MappersHelper::getPaymentsModel($paymentsModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof PaymentsModel);
        $this->assertEquals(self::$_id, $result->id);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_description, $result->description);
    }
    
    /**
     * Тестирует метод MappersHelper::setUsersPurchases
     */
    public function testSetUsersPurchases()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        \Yii::$app->cart->user = new UsersModel();
        \Yii::$app->cart->user->id = self::$_id;
        \Yii::$app->cart->setProductsArray([new ProductsModel(['id'=>self::$_id, 'quantity'=>self::$_quantity, 'colorToCart'=>self::$_id, 'sizeToCart'=>self::$_id])]);
        \Yii::$app->cart->user->deliveries = new DeliveriesModel();
        \Yii::$app->cart->user->deliveries->id = self::$_id;
        \Yii::$app->cart->user->payments = new PaymentsModel();
        \Yii::$app->cart->user->payments->id = self::$_id;
        
        $result = MappersHelper::setUsersPurchases();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users_purchases}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_id, $result[0]['id_users']);
        $this->assertEquals(self::$_id, $result[0]['id_products']);
        $this->assertEquals(self::$_quantity, $result[0]['quantity']);
        $this->assertEquals(self::$_id, $result[0]['id_colors']);
        $this->assertEquals(self::$_id, $result[0]['id_sizes']);
        $this->assertEquals(self::$_id, $result[0]['id_deliveries']);
        $this->assertEquals(self::$_id, $result[0]['id_payments']);
    }
    
    /**
     * Тестирует метод MappersHelper::setOrUpdateUsers
     * в процессе создания записи в БД
     */
    public function testSetOrUpdateUsersOne()
    {
        \Yii::$app->user->login = \Yii::$app->params['nonAuthenticatedUserLogin'];
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} ([[id]],[[rule]]) VALUES (:id1, :rule1), (:id2, :rule2)');
        $command->bindValues([':id1'=>1, ':rule1'=>self::$_rule, ':id2'=>4, ':rule2'=>self::$_rule . self::$_rule]);
        $command->execute();
        
        \Yii::$app->db->createCommand('TRUNCATE TABLE {{users_purchases}}')->execute();
        \Yii::$app->db->createCommand('DELETE FROM {{users}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll()));
        
        $usersModel = new UsersModel();
        $usersModel->login = self::$_login;
        $usersModel->rawPassword = self::$_password;
        $usersModel->name = self::$_name;
        $usersModel->surname = self::$_surname;
        $usersModel->id_emails = self::$_id;
        $usersModel->id_phones = self::$_id;
        $usersModel->id_address = self::$_id;
        
        MappersHelper::setOrUpdateUsers($usersModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_login, $result[0]['login']);
        $this->assertTrue(password_verify(self::$_password, $result[0]['password']));
        $this->assertEquals(self::$_name, $result[0]['name']);
        $this->assertEquals(self::$_surname, $result[0]['surname']);
        $this->assertEquals(self::$_id, $result[0]['id_emails']);
        $this->assertEquals(self::$_id, $result[0]['id_phones']);
        $this->assertEquals(self::$_id, $result[0]['id_address']);
    }
    
    /**
     * Тестирует метод MappersHelper::setOrUpdateUsers
     * в процессе обновления существующей записи из БД
     */
    public function testSetOrUpdateUsersTwo()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll()));
        
        \Yii::$app->user->login = self::$_login;
        \Yii::$app->user->id = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{users}}')->queryScalar();
        
        $usersModel = new UsersModel();
        $usersModel->name = self::$_name;
        $usersModel->surname = self::$_surname;
        $usersModel->id_emails = self::$_id + 12;
        $usersModel->id_phones = self::$_id + 3;
        $usersModel->id_address = self::$_id + 6;
        
        MappersHelper::setOrUpdateUsers($usersModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_login, $result[0]['login']);
        $this->assertTrue(password_verify(self::$_password, $result[0]['password']));
        $this->assertEquals(self::$_name, $result[0]['name']);
        $this->assertEquals(self::$_surname, $result[0]['surname']);
        $this->assertEquals(self::$_id + 12, $result[0]['id_emails']);
        $this->assertEquals(self::$_id + 3, $result[0]['id_phones']);
        $this->assertEquals(self::$_id + 6, $result[0]['id_address']);
    }
    
    /**
     * Тестирует метод MappersHelper::getEmailsModel
     * в процессе создания записи в БД
     */
    public function testGetEmailsModelOne()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails}}')->queryAll()));
        
        $emailsModel = new EmailsModel();
        $emailsModel->email = self::$_email;
        
        MappersHelper::getEmailsModel($emailsModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_email, $result[0]['email']);
    }
    
    /**
     * Тестирует метод MappersHelper::getEmailsModel
     * в процессе получения существующей записи из БД
     */
    public function testGetEmailsModelTwo()
    {
        $emailsModel = new EmailsModel();
        $emailsModel->email = self::$_email;
        
        $result = MappersHelper::getEmailsModel($emailsModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof EmailsModel);
        $this->assertEquals(self::$_email, $result->email);
    }
    
    /**
     * Тестирует метод MappersHelper::setUsersRules
     */
    public function testSetUsersRules()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{users_rules}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{users_rules}}')->queryAll()));
        
        $usersModel = new UsersModel();
        $usersModel->id = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{users}}')->queryScalar();
        
        $result = MappersHelper::setUsersRules($usersModel);
        
        $this->assertEquals(2, $result);
    }
    
    /**
     * Тестирует метод MappersHelper::getCurrencyModelById
     */
    public function testGetCurrencyModelById()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll()));
        
        $currencyModel = new CurrencyModel();
        $currencyModel->id = self::$_id;
        
        $currencyModel = MappersHelper::getCurrencyModelById($currencyModel);
        
        $this->assertTrue(is_object($currencyModel));
        $this->assertTrue($currencyModel instanceof CurrencyModel);
        
        $this->assertTrue(property_exists($currencyModel, 'id'));
        $this->assertTrue(property_exists($currencyModel, 'currency'));
        $this->assertTrue(property_exists($currencyModel, 'exchange_rate'));
        $this->assertTrue(property_exists($currencyModel, 'main'));
        
        $this->assertFalse(empty($currencyModel->id));
        $this->assertFalse(empty($currencyModel->currency));
        $this->assertFalse(empty($currencyModel->exchange_rate));
        $this->assertFalse(empty($currencyModel->main));
        
        $this->assertEquals(self::$_id, $currencyModel->id);
        $this->assertEquals(self::$_currency, $currencyModel->currency);
        $this->assertEquals(self::$_exchange_rate, $currencyModel->exchange_rate);
        $this->assertEquals(self::$_main, $currencyModel->main);
    }
    
    /**
     * Тестирует метод MappersHelper::setCommentsModel
     */
    public function testSetCommentsModel()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{comments}}')->queryAll()));
        
        $id_emails = \Yii::$app->db->createCommand('SELECT * FROM {{emails}}')->queryScalar();
        $id_products = \Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryScalar();
        
        $commentsModel = new CommentsModel();
        $commentsModel->text = self::$_text;
        $commentsModel->name = self::$_name;
        $commentsModel->id_emails = $id_emails;
        $commentsModel->id_products = $id_products;
        
        $result = MappersHelper::setCommentsModel($commentsModel);
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{comments}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertTrue(array_key_exists('id', $result[0]));
        $this->assertTrue(array_key_exists('text', $result[0]));
        $this->assertTrue(array_key_exists('name', $result[0]));
        $this->assertTrue(array_key_exists('id_emails', $result[0]));
        $this->assertTrue(array_key_exists('id_products', $result[0]));
        
        $this->assertEquals(self::$_text, $result[0]['text']);
        $this->assertEquals(self::$_name, $result[0]['name']);
        $this->assertEquals($id_emails, $result[0]['id_emails']);
        $this->assertEquals($id_products, $result[0]['id_products']);
    }
    
    /**
     * Тестирует метод MappersHelper::getProductDetail
     */
    public function testGetProductDetail()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll()));
        
        $id_products = \Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryScalar();
        $_GET = ['id'=>$id_products];
        
        $result = MappersHelper::getProductDetail();
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof ProductsModel);
        
        $this->assertEquals($id_products, $result->id);
        $this->assertEquals(self::$_code, $result->code);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_description, $result->description);
        $this->assertEquals(self::$_price, $result->price);
        $this->assertEquals(self::$_images, $result->images);
    }
    
    /**
     * Тестирует метод MappersHelper::getProductsList
     */
    public function testGetProductsList()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll()));
        
        $result = MappersHelper::getProductsList(self::$_config);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0]  instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод MappersHelper::getProductsList
     * при условии возврата пустого массива
     * @expectedException app\exceptions\EmptyListException
     */
    public function testGetProductsListExc()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{comments}}')->execute();
        \Yii::$app->db->createCommand('DELETE FROM {{products}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll()));
        
        $result = MappersHelper::getProductsList(self::$_config);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
