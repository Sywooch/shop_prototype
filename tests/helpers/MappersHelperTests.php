<?php

namespace app\tests\helpers;

use app\tests\{DbManager, 
    MockObject};
use app\helpers\MappersHelper;
use app\models\{CategoriesModel, 
    CurrencyModel, 
    SubcategoryModel, 
    ColorsModel, 
    SizesModel, 
    BrandsModel, 
    AddressModel, 
    PhonesModel, 
    DeliveriesModel, 
    PaymentsModel, 
    ProductsModel, 
    UsersModel, 
    EmailsModel, 
    CommentsModel, 
    RulesModel, 
    PurchasesModel,
    MailingListModel,
    EmailsMailingListModel,
    AdminMenuModel};

/**
 * Тестирует класс app\helpers\MappersHelper
 */
class MappersHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_id_emails = 42;
    private static $_images = 'images';
    private static $_name = 'Some Name';
    private static $_path = 'products-list/index';
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
    private static $_password = 'iJ7gdJ';
    private static $_surname = 'Some Surname';
    private static $_quantity = 1;
    private static $_rule = 'rule';
    private static $_email = 'some@some.com';
    private static $_currency = 'EUR';
    private static $_exchange_rate = '12.456';
    private static $_main = '1';
    private static $_text = 'Some Text';
    private static $_date = 1462453595;
    private static $_tableName = 'sometable';
    private static $_field = 'somefield';
    private static $_orderByType = 'DESC';
    private static $_route = 'some/index';
    private static $_active = true;
    
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
    
    private static $_searchConfig = [
        'tableName'=>'shop',
        'fields'=>['id'],
    ];
    private static $_search = 'усиленный мыс';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('app\helpers\MappersHelper');
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует наличие свойств и методов в классе app\helpers\MappersHelper
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasProperty('_objectRegistry'));
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
        
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryAll()));
        
        $result = MappersHelper::getColorsList(false);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof ColorsModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_color, $result[0]->color);
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[short_description]]=:short_description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory, [[active]]=:active');
        $command->bindValues([':id'=>self::$_id, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':short_description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id, ':active'=>self::$_active]);
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
     * Тестирует метод MappersHelper::getColorsForProductList
     */
    public function testGetColorsForProductList()
    {
        $id_products = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        
        $productsModel = new ProductsModel();
        $productsModel->id = $id_products;
        
        $result = MappersHelper::getColorsForProductList($productsModel);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof ColorsModel);
    }
    
    /**
     * Тестирует метод MappersHelper::getColorsById
     */
    public function testGetColorsById()
    {
        $id_colors = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{colors}} LIMIT 1')->queryScalar();
        
        $colorsModel = new ColorsModel();
        $colorsModel->id = $id_colors;
        
        $result = MappersHelper::getColorsById($colorsModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof ColorsModel);
        
        $this->assertFalse(empty($result->id));
        $this->assertFalse(empty($result->color));
        
        $this->assertEquals($id_colors, $result->id);
    }
    
    /**
     * Тестирует метод MappersHelper::getSizesList
     */
    public function testGetSizesList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll()));
        
        $result = MappersHelper::getSizesList(false);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof SizesModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_size, $result[0]->size);
        
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
     * Тестирует метод MappersHelper::getSizesById
     */
    public function testGetSizesById()
    {
        $id_sizes = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{sizes}} LIMIT 1')->queryScalar();
        
        $sizesModel = new SizesModel();
        $sizesModel->id = $id_sizes;
        
        $result = MappersHelper::getSizesById($sizesModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof SizesModel);
        
        $this->assertFalse(empty($result->id));
        $this->assertFalse(empty($result->size));
        
        $this->assertEquals($id_sizes, $result->id);
    }
    
    /**
     * Тестирует метод MappersHelper::getSizesForProductList
     */
    public function testGetSizesForProductList()
    {
        $id_products = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        
        $productsModel = new ProductsModel();
        $productsModel->id = $id_products;
        
        $result = MappersHelper::getSizesForProductList($productsModel);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof SizesModel);
    }
    
    /**
     * Тестирует метод MappersHelper::getBrandsList
     */
    public function testGetBrandsList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>self::$_id, ':brand'=>self::$_brand]);
        $command->execute();
        
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_brands}}')->queryAll()));
        
        $result = MappersHelper::getBrandsList(false);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof BrandsModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_brand, $result[0]->brand);
        
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
     * Тестирует метод MappersHelper::setAddressInsert
     */
    public function testSetAddressInsert()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{address}}')->queryAll()));
        
        $addressModel = new AddressModel();
        $addressModel->address = self::$_address;
        $addressModel->city = self::$_city;
        $addressModel->country = self::$_country;
        $addressModel->postcode = self::$_postcode;
        
        MappersHelper::setAddressInsert($addressModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{address}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_address, $result[0]['address']);
        $this->assertEquals(self::$_city, $result[0]['city']);
        $this->assertEquals(self::$_country, $result[0]['country']);
        $this->assertEquals(self::$_postcode, $result[0]['postcode']);
    }
    
    /**
     * Тестирует метод MappersHelper::getAddressByAddress
     */
    public function testGetAddressByAddress()
    {
        $addressModel = new AddressModel();
        $addressModel->address = self::$_address;
        $addressModel->city = self::$_city;
        $addressModel->country = self::$_country;
        $addressModel->postcode = self::$_postcode;
        
        $result = MappersHelper::getAddressByAddress($addressModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof AddressModel);
        $this->assertEquals(self::$_address, $result->address);
        $this->assertEquals(self::$_city, $result->city);
        $this->assertEquals(self::$_country, $result->country);
        $this->assertEquals(self::$_postcode, $result->postcode);
    }
    
    /**
     * Тестирует метод MappersHelper::setPhonesInsert
     */
    public function testSetPhonesInsert()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{phones}}')->queryAll()));
        
        $phonesModel = new PhonesModel();
        $phonesModel->phone = self::$_phone;
        
        MappersHelper::setPhonesInsert($phonesModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{phones}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_phone, $result[0]['phone']);
    }
    
    /**
     * Тестирует метод MappersHelper::getPhonesByPhone
     */
    public function testGetPhonesByPhone()
    {
        $phonesModel = new PhonesModel();
        $phonesModel->phone = self::$_phone;
        
        $result = MappersHelper::getPhonesByPhone($phonesModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof PhonesModel);
        $this->assertEquals(self::$_phone, $result->phone);
    }
    
    /**
     * Тестирует метод MappersHelper::getDeliveriesById
     */
    public function testGetDeliveriesById()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{deliveries}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description, [[price]]=:price');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price]);
        $command->execute();
        
        $deliveriesModel = new DeliveriesModel();
        $deliveriesModel->id = self::$_id;
        
        $result = MappersHelper::getDeliveriesById($deliveriesModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof DeliveriesModel);
        $this->assertEquals(self::$_id, $result->id);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_description, $result->description);
        $this->assertEquals(self::$_price, $result->price);
    }
    
    /**
     * Тестирует метод MappersHelper::getDeliveriesList
     */
    public function testGetDeliveriesList()
    {
        $result = MappersHelper::getDeliveriesList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof DeliveriesModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_name, $result[0]->name);
        $this->assertEquals(self::$_description, $result[0]->description);
        $this->assertEquals(self::$_price, $result[0]->price);
    }
    
    /**
     * Тестирует метод MappersHelper::getPaymentsById
     */
    public function testGetPaymentsById()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{payments}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        $paymentsModel = new PaymentsModel();
        $paymentsModel->id = self::$_id;
        
        $result = MappersHelper::getPaymentsById($paymentsModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof PaymentsModel);
        $this->assertEquals(self::$_id, $result->id);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_description, $result->description);
    }
    
    /**
     * Тестирует метод MappersHelper::getPaymentsList
     */
    public function testGetPaymentsList()
    {
        $result = MappersHelper::getPaymentsList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof PaymentsModel);
    }
    
    /**
     * Тестирует метод MappersHelper::setPurchasesInsert
     */
    public function testSetPurchasesInsert()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id_emails, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id_emails, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        \Yii::$app->cart->user = new UsersModel();
        \Yii::$app->cart->user->id = self::$_id;
        \Yii::$app->cart->setProductsArray([new ProductsModel(['id'=>self::$_id, 'quantity'=>self::$_quantity, 'colorToCart'=>self::$_id, 'sizeToCart'=>self::$_id])]);
        \Yii::$app->cart->user->deliveries = new DeliveriesModel();
        \Yii::$app->cart->user->deliveries->id = self::$_id;
        \Yii::$app->cart->user->payments = new PaymentsModel();
        \Yii::$app->cart->user->payments->id = self::$_id;
        
        $result = MappersHelper::setPurchasesInsert();
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}}')->queryAll();
        
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
     * Тестирует метод MappersHelper::getPurchasesForUserList
     */
    public function testGetPurchasesForUserList()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{purchases}}')->queryAll()));
        
        $model = new UsersModel();
        $model->id = self::$_id;
        
        $result = MappersHelper::getPurchasesForUserList($model);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0]  instanceof PurchasesModel);
    }
    
    /**
     * Тестирует метод MappersHelper::setUsersInsert
     */
    public function testSetUsersInsert()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} ([[id]],[[rule]]) VALUES (:id1, :rule1), (:id2, :rule2)');
        $command->bindValues([':id1'=>1, ':rule1'=>self::$_rule, ':id2'=>4, ':rule2'=>self::$_rule . self::$_rule]);
        $command->execute();
        
        \Yii::$app->db->createCommand('TRUNCATE TABLE {{purchases}}')->execute();
        \Yii::$app->db->createCommand('DELETE FROM {{users}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll()));
        
        $usersModel = new UsersModel();
        $usersModel->id_emails = self::$_id_emails;
        $usersModel->rawPassword = self::$_password;
        $usersModel->name = self::$_name;
        $usersModel->surname = self::$_surname;
        $usersModel->id_phones = self::$_id;
        $usersModel->id_address = self::$_id;
        
        MappersHelper::setUsersInsert($usersModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_id_emails, $result[0]['id_emails']);
        $this->assertTrue(password_verify(self::$_password, $result[0]['password']));
        $this->assertEquals(self::$_name, $result[0]['name']);
        $this->assertEquals(self::$_surname, $result[0]['surname']);
        $this->assertEquals(self::$_id, $result[0]['id_phones']);
        $this->assertEquals(self::$_id, $result[0]['id_address']);
    }
    
    /**
     * Тестирует метод MappersHelper::setUsersUpdate
     */
    public function testSetUsersUpdate()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll()));
        
        $id = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{users}} LIMIT 1')->queryScalar();
        
        $usersModel = new UsersModel();
        $usersModel->id = $id;
        $usersModel->name = self::$_name;
        $usersModel->surname = self::$_surname;
        $usersModel->id_emails = self::$_id_emails;
        $usersModel->id_phones = self::$_id + 3;
        $usersModel->id_address = self::$_id + 6;
        
        MappersHelper::setUsersUpdate($usersModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(password_verify(self::$_password, $result[0]['password']));
        $this->assertEquals(self::$_name, $result[0]['name']);
        $this->assertEquals(self::$_surname, $result[0]['surname']);
        $this->assertEquals(self::$_id_emails, $result[0]['id_emails']);
        $this->assertEquals(self::$_id + 3, $result[0]['id_phones']);
        $this->assertEquals(self::$_id + 6, $result[0]['id_address']);
    }
    
    /**
     * Тестирует метод MappersHelper::setEmailsInsert
     */
    public function testSetEmailsInsert()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{emails}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails}}')->queryAll()));
        
        $emailsModel = new EmailsModel();
        $emailsModel->email = self::$_email;
        
        MappersHelper::setEmailsInsert($emailsModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_email, $result[0]['email']);
    }
    
    /**
     * Тестирует метод MappersHelper::getEmailsByEmail
     */
    public function testGetEmailsByEmail()
    {
        $emailsModel = new EmailsModel();
        $emailsModel->email = self::$_email;
        
        $result = MappersHelper::getEmailsByEmail($emailsModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof EmailsModel);
        $this->assertEquals(self::$_email, $result->email);
    }
    
    /**
     * Тестирует метод MappersHelper::setUsersRulesInsert
     */
    public function testSetUsersRulesInsert()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{emails}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails}}')->queryAll()));
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{users}}')->queryAll()));
        
        \Yii::$app->db->createCommand('DELETE FROM {{users_rules}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{users_rules}}')->queryAll()));
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id_emails, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id_emails, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        $usersModel = new UsersModel();
        $usersModel->id = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{users}} LIMIT 1')->queryScalar();
        
        $result = MappersHelper::setUsersRulesInsert($usersModel);
        
        $this->assertEquals(2, $result);
    }
    
    /**
     * Тестирует метод MappersHelper::getCurrencyById
     */
    public function testGetCurrencyById()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll()));
        
        $currencyModel = new CurrencyModel();
        $currencyModel->id = self::$_id;
        
        $currencyModel = MappersHelper::getCurrencyById($currencyModel);
        
        $this->assertTrue(is_object($currencyModel));
        $this->assertTrue($currencyModel instanceof CurrencyModel);
        
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
     * Тестирует метод MappersHelper::setCommentsInsert
     */
    public function testSetCommentsInsert()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{comments}}')->queryAll()));
        
        $id_emails = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{emails}} LIMIT 1')->queryScalar();
        $id_products = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        
        $commentsModel = new CommentsModel();
        $commentsModel->text = self::$_text;
        $commentsModel->name = self::$_name;
        $commentsModel->id_emails = $id_emails;
        $commentsModel->id_products = $id_products;
        
        $result = MappersHelper::setCommentsInsert($commentsModel);
        
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
     * Тестирует метод MappersHelper::getProductsSearch
     * !!!ВАЖНО Поскольку индекс sphynx получает данные из рабочей БД, для успешного прохождения теста убедитесь, 
     * что БД содержит запись, удовлетворяющую условиям поиска из self::$_search
     */
    public function testGetProductsSearch()
    {
        $_GET = [\Yii::$app->params['searchKey']=>self::$_search];
        
        $result = MappersHelper::getProductsSearch(self::$_searchConfig);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0]  instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод MappersHelper::getRulesList
     */
    public function testGetRulesList()
    {
        $result = MappersHelper::getRulesList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof RulesModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_rule, $result[0]->rule);
    }
    
    /**
     * Тестирует метод MappersHelper::getEmailsById
     */
    public function testGetEmailsById()
    {
        $this->assertFalse(empty($id_emails = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{emails}} LIMIT 1')->queryScalar()));
        
        $emailsModel = new EmailsModel();
        $emailsModel->id = $id_emails;
        
        $result = MappersHelper::getEmailsById($emailsModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof EmailsModel);
        $this->assertEquals($id_emails, $result->id);
        $this->assertEquals(self::$_email, $result->email);
    }
    
    /**
     * Тестирует метод MappersHelper::getPhonesById
     */
    public function testGetPhonesById()
    {
        $this->assertFalse(empty($id_phone = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{phones}} LIMIT 1')->queryScalar()));
        
        $phonesModel = new PhonesModel();
        $phonesModel->id = $id_phone;
        
        $result = MappersHelper::getPhonesById($phonesModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof PhonesModel);
        $this->assertEquals($id_phone, $result->id);
        $this->assertEquals(self::$_phone, $result->phone);
    }
    
    /**
     * Тестирует метод MappersHelper::getAddressById
     */
    public function testGetAddressById()
    {
        $this->assertFalse(empty($id_address = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{address}} LIMIT 1')->queryScalar()));
        
        $addressModel = new AddressModel();
        $addressModel->id = $id_address;
        
        $result = MappersHelper::getAddressById($addressModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof AddressModel);
        $this->assertEquals($id_address, $result->id);
        $this->assertEquals(self::$_address, $result->address);
        $this->assertEquals(self::$_city, $result->city);
        $this->assertEquals(self::$_country, $result->country);
        $this->assertEquals(self::$_postcode, $result->postcode);
    }
    
    /**
     * Тестирует метод MappersHelper::getCurrencyByMain
     */
    public function testGetCurrencyByMain()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT [[main]] FROM {{currency}} LIMIT 1')->queryScalar()));
        
        $result = MappersHelper::getCurrencyByMain();
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof CurrencyModel);
        $this->assertEquals(1, $result->main);
    }
    
    /**
     * Тестирует метод MappersHelper::getSubcategoryForCategoryList
     */
    public function testGetSubcategoryForCategoryList()
    {
        $categoriesModel = new CategoriesModel();
        $categoriesModel->id = self::$_id;
        
        $result = MappersHelper::getSubcategoryForCategoryList($categoriesModel);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof SubcategoryModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_name, $result[0]->name);
        $this->assertEquals(self::$_subcategorySeocode, $result[0]->seocode);
        $this->assertEquals(self::$_id, $result[0]->id_categories);
    }
    
    /**
     * Тестирует метод MappersHelper::getSimilarProductsList
     */
    public function testGetSimilarProductsList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory, [[active]]=:active');
        $command->bindValues([':id'=>self::$_id + 23, ':date'=>self::$_date, ':code'=>self::$_code . 'n', ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id, ':active'=>self::$_active]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id + 23, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id + 23, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        $_GET = ['id'=>self::$_id, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        $id_products = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        
        $productsModel = new ProductsModel();
        $productsModel->id = $id_products;
        
        $result = MappersHelper::getSimilarProductsList($productsModel);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод MappersHelper::getRelatedProductsList
     */
    public function testGetRelatedProductsList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{related_products}} SET [[id_products]]=:id_products, [[id_related_products]]=:id_related_products');
        $command->bindValues([':id_products'=>self::$_id, ':id_related_products'=>self::$_id + 23]);
        $command->execute();
        
        $productsModel = new ProductsModel();
        $productsModel->id = self::$_id;
        
        $result = MappersHelper::getRelatedProductsList($productsModel);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод MappersHelper::getCommentsForProductList
     */
    public function testGetCommentsForProductList()
    {
        $id_products = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        
        $productsModel = new ProductsModel();
        $productsModel->id = $id_products;
        
        $result = MappersHelper::getCommentsForProductList($productsModel);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof CommentsModel);
    }
    
    /**
     * Тестирует метод MappersHelper::getUsersByIdEmails
     */
    public function testGetUsersByIdEmails()
    {
        $usersModel = new UsersModel();
        $usersModel->id_emails = self::$_id_emails;
        
        $result = MappersHelper::getUsersByIdEmails($usersModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof UsersModel);
    }
    
    /**
     * Тестирует метод MappersHelper::getUsersById
     */
    public function testGetUsersById()
    {
        $user_id = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{users}} LIMIT 1')->queryScalar();
        $this->assertFalse(empty($user_id));
        
        $usersModel = new UsersModel();
        $usersModel->id = $user_id;
        
        $result = MappersHelper::getUsersById($usersModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof UsersModel);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_surname, $result->surname);
    }
    
    /**
     * Тестирует метод MappersHelper::setProductsInsert
     */
    public function testSetProductsInsert()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{comments}}')->execute();
        \Yii::$app->db->createCommand('DELETE FROM {{products}}')->execute();
        
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll()));
        
        $productsModel = new ProductsModel();
        $productsModel->date = self::$_date;
        $productsModel->code = self::$_code;
        $productsModel->name = self::$_name;
        $productsModel->description = self::$_description;
        $productsModel->short_description = self::$_description;
        $productsModel->price = self::$_price;
        $productsModel->images = self::$_images;
        $productsModel->id_categories = self::$_id;
        $productsModel->id_subcategory = self::$_id;
        
        MappersHelper::setProductsInsert($productsModel);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products}}')->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(self::$_code, $result[0]['code']);
        $this->assertEquals(self::$_name, $result[0]['name']);
        $this->assertEquals(self::$_description, $result[0]['description']);
        $this->assertEquals(self::$_description, $result[0]['short_description']);
        $this->assertEquals(self::$_price, $result[0]['price']);
        $this->assertEquals(self::$_images, $result[0]['images']);
        $this->assertEquals(self::$_id, $result[0]['id_categories']);
        $this->assertEquals(self::$_id, $result[0]['id_subcategory']);
    }
    
    /**
     * Тестирует метод MappersHelper::setProductsUpdate
     */
    public function testSetProductsUpdate()
    {
        $this->assertFalse(empty($id = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar()));
        
        $productsModel = new ProductsModel();
        $productsModel->id = $id;
        $productsModel->date = self::$_date;
        $productsModel->code = self::$_code;
        $productsModel->name = self::$_name . ' another';
        $productsModel->description = self::$_description;
        $productsModel->short_description = self::$_description;
        $productsModel->price = round(self::$_price * 23.4, 2, PHP_ROUND_HALF_UP);
        $productsModel->images = self::$_images;
        $productsModel->id_categories = self::$_id;
        $productsModel->id_subcategory = self::$_id;
        $productsModel->active = self::$_active * 0;
        
        $result = MappersHelper::setProductsUpdate([$productsModel]);
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE [[id]]=:id');
        $command->bindValue(':id', $id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals($id, $result['id']);
        $this->assertEquals(self::$_date, $result['date']);
        $this->assertEquals(self::$_code, $result['code']);
        $this->assertEquals(self::$_name . ' another', $result['name']);
        $this->assertEquals(self::$_description, $result['description']);
        $this->assertEquals(self::$_description, $result['short_description']);
        $this->assertEquals(round(self::$_price * 23.4, 2, PHP_ROUND_HALF_UP), round($result['price'], 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(self::$_images, $result['images']);
        $this->assertEquals(self::$_id, $result['id_categories']);
        $this->assertEquals(self::$_id, $result['id_subcategory']);
        $this->assertEquals(self::$_active * 0, $result['active']);
    }
    
    /**
     * Тестирует метод MappersHelper::getProductsByCode
     */
    public function testGetProductsByCode()
    {
        $productsModel = new ProductsModel();
        $productsModel->code = self::$_code;
        
        $result = MappersHelper::getProductsByCode($productsModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof ProductsModel);
        $this->assertEquals(self::$_code, $result->code);
    }
    
    /**
     * Тестирует метод MappersHelper::getProductsById
     */
    public function testGetProductsById()
    {
        $productsModel = new ProductsModel();
        $productsModel->id = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        
        $result = MappersHelper::getProductsById($productsModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof ProductsModel);
        $this->assertEquals($productsModel->id, $result->id);
    }
    
    /**
     * Тестирует метод MappersHelper::getCategoriesById
     */
    public function testGetCategoriesById()
    {
        $categoriesModel = new CategoriesModel();
        $categoriesModel->id = self::$_id;
        
        $result = MappersHelper::getCategoriesById($categoriesModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof CategoriesModel);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_categorySeocode, $result->seocode);
    }
    
    /**
     * Тестирует метод MappersHelper::getCategoriesBySeocode
     */
    public function testGetCategoriesBySeocode()
    {
        $categoriesModel = new CategoriesModel();
        $categoriesModel->seocode = self::$_categorySeocode;
        
        $result = MappersHelper::getCategoriesBySeocode($categoriesModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof CategoriesModel);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_categorySeocode, $result->seocode);
    }
    
    /**
     * Тестирует метод MappersHelper::getSubcategoryById
     */
    public function testGetSubcategoryById()
    {
        $subcategoryModel = new SubcategoryModel();
        $subcategoryModel->id = self::$_id;
        
        $result = MappersHelper::getSubcategoryById($subcategoryModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof SubcategoryModel);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_subcategorySeocode, $result->seocode);
        $this->assertEquals(self::$_id, $result->id_categories);
    }
    
    /**
     * Тестирует метод MappersHelper::getSubcategoryBySeocode
     */
    public function testGetSubcategoryBySeocode()
    {
        $subcategoryModel = new SubcategoryModel();
        $subcategoryModel->seocode = self::$_subcategorySeocode;
        
        $result = MappersHelper::getSubcategoryBySeocode($subcategoryModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof SubcategoryModel);
        $this->assertEquals(self::$_name, $result->name);
        $this->assertEquals(self::$_subcategorySeocode, $result->seocode);
        $this->assertEquals(self::$_id, $result->id_categories);
    }
    
    /**
     * Тестирует метод MappersHelper::setProductsBrandsInsert
     */
    public function testSetProductsBrandsInsert()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_brands}}')->queryAll()));
        
        $id_products = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        $id_brands = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{brands}} LIMIT 1')->queryScalar();
        
        $productsModel = new ProductsModel();
        $productsModel->id = $id_products;
        
        $brandsModel = new BrandsModel();
        $brandsModel->id = $id_brands;
        
        $result = MappersHelper::setProductsBrandsInsert($productsModel, $brandsModel);
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_brands}}')->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals($id_products, $result['id_products']);
        $this->assertEquals($id_brands, $result['id_brands']);
    }
    
    /**
     * Тестирует метод MappersHelper::setProductsColorsInsert
     */
    public function testSetProductsColorsInsert()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryAll()));
        
        $id_products = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        $id_colors = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{colors}} LIMIT 1')->queryScalar();
        
        $productsModel = new ProductsModel();
        $productsModel->id = $id_products;
        
        $colorsModel = new ColorsModel();
        $colorsModel->idArray = [$id_colors];
        
        $result = MappersHelper::setProductsColorsInsert($productsModel, $colorsModel);
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals($id_products, $result['id_products']);
        $this->assertEquals($id_colors, $result['id_colors']);
    }
    
    /**
     * Тестирует метод MappersHelper::setProductsSizesInsert
     */
    public function testSetProductsSizesInsert()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll()));
        
        $id_products = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{products}} LIMIT 1')->queryScalar();
        $id_sizes = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{sizes}} LIMIT 1')->queryScalar();
        
        $productsModel = new ProductsModel();
        $productsModel->id = $id_products;
        
        $sizesModel = new SizesModel();
        $sizesModel->idArray = [$id_sizes];
        
        $result = MappersHelper::setProductsSizesInsert($productsModel, $sizesModel);
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals($id_products, $result['id_products']);
        $this->assertEquals($id_sizes, $result['id_sizes']);
    }
    
    /**
     * Тестирует метод MappersHelper::getMailingList
     */
    public function testGetMailingList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{mailing_list}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        $result = MappersHelper::getMailingList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof MailingListModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_name, $result[0]->name);
        $this->assertEquals(self::$_description, $result[0]->description);
    }
    
    /**
     * Тестирует метод MappersHelper::setEmailsMailingListInsert
     */
    public function testSetEmailsMailingListInsert()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}}')->queryAll()));
        
        $id_emails = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{emails}} LIMIT 1')->queryScalar();
        $id_mailing_list = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{mailing_list}} LIMIT 1')->queryScalar();
        
        $emailsModel = new EmailsModel();
        $emailsModel->id = $id_emails;
        
        $mailingListModel = new MailingListModel();
        $mailingListModel->idFromForm = [$id_mailing_list];
        
        $result = MappersHelper::setEmailsMailingListInsert($emailsModel, $mailingListModel);
        
        $this->assertEquals(1, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}}')->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals($id_emails, $result['id_email']);
        $this->assertEquals($id_mailing_list, $result['id_mailing_list']);
    }
    
    /**
     * Тестирует метод MappersHelper::getMailingListById
     */
    public function testGetMailingListById()
    {
        $id_mailingList = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{mailing_list}} LIMIT 1')->queryScalar();
        
        $mailingListModel = new MailingListModel();
        $mailingListModel->id = $id_mailingList;
        
        $result = MappersHelper::getMailingListById($mailingListModel);
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof MailingListModel);
        
        $this->assertFalse(empty($result->id));
        $this->assertFalse(empty($result->name));
        $this->assertFalse(empty($result->description));
        
        $this->assertEquals($id_mailingList, $result->id);
    }
    
    /**
     * Тестирует метод MappersHelper::getMailingListForEmail
     */
    public function testGetMailingListForEmail()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{mailing_list}}')->queryAll()));
        $email = \Yii::$app->db->createCommand('SELECT [[email]] FROM {{emails}} LIMIT 1')->queryScalar();
        
        $model = new EmailsModel();
        $model->email = $email;
        
        $result = MappersHelper::getMailingListForEmail($model);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0]  instanceof MailingListModel);
    }
    
    /**
     * Тестирует метод MappersHelper::setEmailsMailingListDelete
     */
    public function testSetEmailsMailingListDelete()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails}}')->queryAll()));
        $id_email = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{emails}} LIMIT 1')->queryScalar();
        
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{mailing_list}}')->queryAll()));
        $id_mailing_list = \Yii::$app->db->createCommand('SELECT [[id]] FROM {{mailing_list}} LIMIT 1')->queryScalar();
        
        \Yii::$app->db->createCommand('DELETE FROM {{emails_mailing_list}}')->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails_mailing_list}} SET [[id_email]]=:id_email, [[id_mailing_list]]=:id_mailing_list');
        $command->bindValues([':id_email'=>$id_email, ':id_mailing_list'=>$id_mailing_list]);
        $command->execute();
        
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}}')->queryAll()));
        
        $model = new EmailsMailingListModel(['id_email'=>$id_email, 'id_mailing_list'=>$id_mailing_list]);
        
        $result = MappersHelper::setEmailsMailingListDelete([$model]);
        
        $this->assertEquals(1, $result);
        
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}}')->queryAll()));
    }
    
    /**
     * Тестирует метод MappersHelper::getAdminMenuList
     */
    public function testGetAdminMenuList()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{admin_menu}} SET [[id]]=:id, [[name]]=:name, [[route]]=:route');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name,  ':route'=>self::$_route]);
        $command->execute();
        
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{admin_menu}}')->queryAll()));
        
        $result = MappersHelper::getAdminMenuList();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(is_object($result[0]));
        $this->assertTrue($result[0] instanceof AdminMenuModel);
        $this->assertEquals(self::$_id, $result[0]->id);
        $this->assertEquals(self::$_name, $result[0]->name);
        $this->assertEquals(self::$_route, $result[0]->route);
    }
    
    /**
     * Тестирует метод MappersHelper::getObjectRegistry
     */
    public function testGetObjectRegistry()
    {
        $result = MappersHelper::getObjectRegistry();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
    }
    
    /**
     * Тестирует метод MappersHelper::cleanProperties
     */
    public function testCleanProperties()
    {
        $result = MappersHelper::cleanProperties();
        $this->assertTrue($result);
        
        $result = MappersHelper::getObjectRegistry();
        $this->assertTrue(empty($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
