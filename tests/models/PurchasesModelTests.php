<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\PurchasesModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\{AddressFixture,
    CitiesFixture,
    ColorsFixture,
    CountriesFixture,
    DeliveriesFixture,
    EmailsFixture,
    NamesFixture,
    PaymentsFixture,
    PhonesFixture,
    PostcodesFixture,
    ProductsFixture,
    SurnamesFixture,
    SizesFixture};
use app\models\{AddressModel,
    CitiesModel,
    ColorsModel,
    CountriesModel,
    DeliveriesModel,
    EmailsModel,
    NamesModel,
    PaymentsModel,
    PhonesModel,
    PostcodesModel,
    ProductsModel,
    SurnamesModel,
    SizesModel};

/**
 * Тестирует класс PurchasesModel
 */
class PurchasesModelTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'address'=>AddressFixture::class,
                'products'=>ProductsFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'names'=>NamesFixture::class,
                'surnames'=>SurnamesFixture::class,
                'cities'=>CitiesFixture::class,
                'countries'=>CountriesFixture::class,
                'postcodes'=>PostcodesFixture::class,
                'phones'=>PhonesFixture::class,
                'payments'=>PaymentsFixture::class,
                'deliveries'=>DeliveriesFixture::class,
                'emails'=>EmailsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PurchasesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchasesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        $this->assertTrue($reflection->hasConstant('UPDATE'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('CANCEL'));
        $this->assertTrue($reflection->hasConstant('UPDATE_STATUS'));
        
        $model = new PurchasesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('id_user', $model->attributes);
        $this->assertArrayHasKey('id_name', $model->attributes);
        $this->assertArrayHasKey('id_surname', $model->attributes);
        $this->assertArrayHasKey('id_email', $model->attributes);
        $this->assertArrayHasKey('id_phone', $model->attributes);
        $this->assertArrayHasKey('id_address', $model->attributes);
        $this->assertArrayHasKey('id_city', $model->attributes);
        $this->assertArrayHasKey('id_country', $model->attributes);
        $this->assertArrayHasKey('id_postcode', $model->attributes);
        $this->assertArrayHasKey('id_product', $model->attributes); 
        $this->assertArrayHasKey('quantity', $model->attributes); 
        $this->assertArrayHasKey('id_color', $model->attributes); 
        $this->assertArrayHasKey('id_size', $model->attributes);
        $this->assertArrayHasKey('price', $model->attributes); 
        $this->assertArrayHasKey('id_delivery', $model->attributes); 
        $this->assertArrayHasKey('id_payment', $model->attributes); 
        $this->assertArrayHasKey('received', $model->attributes); 
        $this->assertArrayHasKey('received_date', $model->attributes);
        $this->assertArrayHasKey('processed', $model->attributes); 
        $this->assertArrayHasKey('canceled', $model->attributes); 
        $this->assertArrayHasKey('shipped', $model->attributes);
    }
    
    /**
     * Тестирует метод PurchasesModel::tableName
     */
    public function testTableName()
    {
        $result = PurchasesModel::tableName();
        
        $this->assertSame('purchases', $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::scenarios
     */
    public function testScenarios()
    {
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
        $model->attributes = [
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(34, $result['id_product']);
        $this->assertArrayHasKey('quantity', $result);
        $this->assertSame(2, $result['quantity']);
        $this->assertArrayHasKey('id_color', $result);
        $this->assertSame(4, $result['id_color']);
        $this->assertArrayHasKey('id_size', $result);
        $this->assertSame(2, $result['id_size']);
        $this->assertArrayHasKey('price', $result);
        $this->assertSame(245.98, $result['price']);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
        $model->attributes = [
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(34, $result['id_product']);
        $this->assertArrayHasKey('quantity', $result);
        $this->assertSame(2, $result['quantity']);
        $this->assertArrayHasKey('id_color', $result);
        $this->assertSame(4, $result['id_color']);
        $this->assertArrayHasKey('id_size', $result);
        $this->assertSame(2, $result['id_size']);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::DELETE]);
        $model->attributes = [
            'id_product'=>2, 
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(2, $result['id_product']);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SAVE]);
        $model->attributes = [
            'id_user'=>2,
            'id_name'=>3,
            'id_surname'=>1,
            'id_email'=>12,
            'id_phone'=>4,
            'id_address'=>2,
            'id_city'=>6,
            'id_country'=>7,
            'id_postcode'=>2,
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
            'id_delivery'=>2, 
            'id_payment'=>1, 
            'received'=>1, 
            'received_date'=>1458471063,
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('id_user', $result);
        $this->assertSame(2, $result['id_user']);
        $this->assertArrayHasKey('id_name', $result);
        $this->assertSame(3, $result['id_name']);
        $this->assertArrayHasKey('id_surname', $result);
        $this->assertSame(1, $result['id_surname']);
        $this->assertArrayHasKey('id_email', $result);
        $this->assertSame(12, $result['id_email']);
        $this->assertArrayHasKey('id_phone', $result);
        $this->assertSame(4, $result['id_phone']);
        $this->assertArrayHasKey('id_address', $result);
        $this->assertSame(2, $result['id_address']);
        $this->assertArrayHasKey('id_city', $result);
        $this->assertSame(6, $result['id_city']);
        $this->assertArrayHasKey('id_country', $result);
        $this->assertSame(7, $result['id_country']);
        $this->assertArrayHasKey('id_postcode', $result);
        $this->assertSame(2, $result['id_postcode']);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(34, $result['id_product']);
        $this->assertArrayHasKey('quantity', $result);
        $this->assertSame(2, $result['quantity']);
        $this->assertArrayHasKey('id_color', $result);
        $this->assertSame(4, $result['id_color']);
        $this->assertArrayHasKey('id_size', $result);
        $this->assertSame(2, $result['id_size']);
        $this->assertArrayHasKey('price', $result);
        $this->assertSame(245.98, $result['price']);
        $this->assertArrayHasKey('id_delivery', $result);
        $this->assertSame(2, $result['id_delivery']);
        $this->assertArrayHasKey('id_payment', $result);
        $this->assertSame(1, $result['id_payment']);
        $this->assertArrayHasKey('received', $result);
        $this->assertSame(1, $result['received']);
        $this->assertArrayHasKey('received_date', $result);
        $this->assertSame(1458471063, $result['received_date']);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::CANCEL]);
        $model->attributes = [
            'canceled'=>true,
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('canceled', $result);
        $this->assertTrue($result['canceled']);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE_STATUS]);
        $model->attributes = [
            'received'=>true,
            'processed'=>true,
            'canceled'=>true,
            'shipped'=>true,
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('received', $result);
        $this->assertTrue($result['received']);
        $this->assertArrayHasKey('processed', $result);
        $this->assertTrue($result['processed']);
        $this->assertArrayHasKey('canceled', $result);
        $this->assertTrue($result['canceled']);
        $this->assertArrayHasKey('shipped', $result);
        $this->assertTrue($result['shipped']);
    }
    
    /**
     * Тестирует метод PurchasesModel::rules
     */
    public function testRules()
    {
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(5, $model->errors);
        $this->assertArrayHasKey('id_product', $model->errors);
        $this->assertArrayHasKey('quantity', $model->errors);
        $this->assertArrayHasKey('id_color', $model->errors);
        $this->assertArrayHasKey('id_size', $model->errors);
        $this->assertArrayHasKey('price', $model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
        $model->attributes = [
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(4, $model->errors);
        $this->assertArrayHasKey('id_product', $model->errors);
        $this->assertArrayHasKey('quantity', $model->errors);
        $this->assertArrayHasKey('id_color', $model->errors);
        $this->assertArrayHasKey('id_size', $model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
        $model->attributes = [
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        $this->assertArrayHasKey('id_product', $model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::DELETE]);
        $model->attributes = [
            'id_product'=>34, 
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(17, $model->errors);
        $this->assertArrayHasKey('id_name', $model->errors);
        $this->assertArrayHasKey('id_surname', $model->errors);
        $this->assertArrayHasKey('id_email', $model->errors);
        $this->assertArrayHasKey('id_phone', $model->errors);
        $this->assertArrayHasKey('id_address', $model->errors);
        $this->assertArrayHasKey('id_city', $model->errors);
        $this->assertArrayHasKey('id_country', $model->errors);
        $this->assertArrayHasKey('id_postcode', $model->errors);
        $this->assertArrayHasKey('id_product', $model->errors);
        $this->assertArrayHasKey('quantity', $model->errors);
        $this->assertArrayHasKey('id_color', $model->errors);
        $this->assertArrayHasKey('id_size', $model->errors);
        $this->assertArrayHasKey('price', $model->errors);
        $this->assertArrayHasKey('id_delivery', $model->errors);
        $this->assertArrayHasKey('id_payment', $model->errors);
        $this->assertArrayHasKey('received', $model->errors);
        $this->assertArrayHasKey('received_date', $model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SAVE]);
        $model->attributes = [
            'id_name'=>3,
            'id_surname'=>1,
            'id_email'=>12,
            'id_phone'=>4,
            'id_address'=>2,
            'id_city'=>6,
            'id_country'=>7,
            'id_postcode'=>2,
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
            'id_delivery'=>2, 
            'id_payment'=>1, 
            'received'=>1, 
            'received_date'=>1458471063,
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertEquals(0, $model['id_user']);
    }
    
    /**
     * Тестирует метод PurchasesModel::getProduct
     */
    public function testGetProduct()
    {
        $model = new PurchasesModel();
        $model->id_product = 1;
        
        $result = $model->product;
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getColor
     */
    public function testGetColor()
    {
        $model = new PurchasesModel();
        $model->id_color = 1;
        
        $result = $model->color;
        
        $this->assertInstanceOf(ColorsModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getSize
     */
    public function testGetSize()
    {
        $model = new PurchasesModel();
        $model->id_size = 1;
        
        $result = $model->size;
        
        $this->assertInstanceOf(SizesModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getName
     */
    public function testGetName()
    {
        $model = new PurchasesModel();
        $model->id_name = 1;
        
        $result = $model->name;
        
        $this->assertInstanceOf(NamesModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getSurname
     */
    public function testGetSurname()
    {
        $model = new PurchasesModel();
        $model->id_surname = 1;
        
        $result = $model->surname;
        
        $this->assertInstanceOf(SurnamesModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getAddress
     */
    public function testGetAddress()
    {
        $model = new PurchasesModel();
        $model->id_address = 1;
        
        $result = $model->address;
        
        $this->assertInstanceOf(AddressModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getCity
     */
    public function testGetCity()
    {
        $model = new PurchasesModel();
        $model->id_city = 1;
        
        $result = $model->city;
        
        $this->assertInstanceOf(CitiesModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getCountries
     */
    public function testGetCountry()
    {
        $model = new PurchasesModel();
        $model->id_country = 1;
        
        $result = $model->country;
        
        $this->assertInstanceOf(CountriesModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getPostcode
     */
    public function testGetPostcode()
    {
        $model = new PurchasesModel();
        $model->id_postcode = 1;
        
        $result = $model->postcode;
        
        $this->assertInstanceOf(PostcodesModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getPhone
     */
    public function testGetPhone()
    {
        $model = new PurchasesModel();
        $model->id_phone = 1;
        
        $result = $model->phone;
        
        $this->assertInstanceOf(PhonesModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getPayments
     */
    public function testGetPayment()
    {
        $model = new PurchasesModel();
        $model->id_payment = 1;
        
        $result = $model->payment;
        
        $this->assertInstanceOf(PaymentsModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getDeliveries
     */
    public function testGetDelivery()
    {
        $model = new PurchasesModel();
        $model->id_delivery = 1;
        
        $result = $model->delivery;
        
        $this->assertInstanceOf(DeliveriesModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getEmail
     */
    public function testGetEmail()
    {
        $model = new PurchasesModel();
        $model->id_email = 1;
        
        $result = $model->email;
        
        $this->assertInstanceOf(EmailsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
