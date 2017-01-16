<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{AddressModel,
    CountriesModel,
    CitiesModel,
    EmailsModel,
    NamesModel,
    PhonesModel,
    PostcodesModel,
    SurnamesModel,
    UsersModel};
use app\tests\sources\fixtures\UsersFixture;

/**
 * Тестирует класс UsersModel
 */
class UsersModelTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует наличие свойств у объекта UsersModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UsersModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('UPDATE'));
        $this->assertTrue($reflection->hasConstant('UPDATE_PASSW'));
        
        $model = new UsersModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('id_email', $model->attributes));
        $this->assertTrue(array_key_exists('password', $model->attributes));
        $this->assertTrue(array_key_exists('id_name', $model->attributes));
        $this->assertTrue(array_key_exists('id_surname', $model->attributes));
        $this->assertTrue(array_key_exists('id_phone', $model->attributes));
        $this->assertTrue(array_key_exists('id_address', $model->attributes));
        $this->assertTrue(array_key_exists('id_city', $model->attributes));
        $this->assertTrue(array_key_exists('id_country', $model->attributes));
        $this->assertTrue(array_key_exists('id_postcode', $model->attributes));
    }
    
    /**
     * Тестирует метод UsersModel::scenarios
     */
    public function testScenarios()
    {
        $model = new UsersModel(['scenario'=>UsersModel::SAVE]);
        $model->attributes = [
            'id_email'=>1,
            'password'=>'password',
            'id_name'=>1,
            'id_surname'=>1,
            'id_phone'=>1,
            'id_address'=>1,
            'id_city'=>1,
            'id_country'=>1,
            'id_postcode'=>1
        ];
        
        $this->assertSame(1, $model->id_email);
        $this->assertSame('password', $model->password);
        $this->assertSame(1, $model->id_name);
        $this->assertSame(1, $model->id_surname);
        $this->assertSame(1, $model->id_phone);
        $this->assertSame(1, $model->id_address);
        $this->assertSame(1, $model->id_city);
        $this->assertSame(1, $model->id_country);
        $this->assertSame(1, $model->id_postcode);
        
        $model = new UsersModel(['scenario'=>UsersModel::UPDATE]);
        $model->attributes = [
            'id_name'=>1,
            'id_surname'=>1,
            'id_phone'=>1,
            'id_address'=>1,
            'id_city'=>1,
            'id_country'=>1,
            'id_postcode'=>1
        ];
        
        $this->assertSame(1, $model->id_name);
        $this->assertSame(1, $model->id_surname);
        $this->assertSame(1, $model->id_phone);
        $this->assertSame(1, $model->id_address);
        $this->assertSame(1, $model->id_city);
        $this->assertSame(1, $model->id_country);
        $this->assertSame(1, $model->id_postcode);
        
        $model = new UsersModel(['scenario'=>UsersModel::UPDATE_PASSW]);
        $model->attributes = [
            'password'=>'password',
        ];
        
        $this->assertSame('password', $model->password);
    }
    
    /**
     * Тестирует метод UsersModel::rules
     */
    public function testRules()
    {
        $model = new UsersModel(['scenario'=>UsersModel::SAVE]);
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(2, $model->errors);
        $this->assertArrayHasKey('id_email', $model->errors);
        $this->assertArrayHasKey('password', $model->errors);
        
        $model = new UsersModel();
        
        $this->assertSame(null, $model->id_name);
        $this->assertSame(null, $model->id_surname);
        $this->assertSame(null, $model->id_phone);
        $this->assertSame(null, $model->id_address);
        $this->assertSame(null, $model->id_city);
        $this->assertSame(null, $model->id_country);
        $this->assertSame(null, $model->id_postcode);
        
        $model = new UsersModel(['scenario'=>UsersModel::SAVE]);
        $model->attributes = [
            'id_email'=>1,
            'password'=>'password',
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->id_name);
        $this->assertSame(0, $model->id_surname);
        $this->assertSame(0, $model->id_phone);
        $this->assertSame(0, $model->id_address);
        $this->assertSame(0, $model->id_city);
        $this->assertSame(0, $model->id_country);
        $this->assertSame(0, $model->id_postcode);
        
        $model = new UsersModel(['scenario'=>UsersModel::UPDATE]);
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(7, $model->errors);
        $this->assertArrayHasKey('id_name', $model->errors);
        $this->assertArrayHasKey('id_surname', $model->errors);
        $this->assertArrayHasKey('id_phone', $model->errors);
        $this->assertArrayHasKey('id_address', $model->errors);
        $this->assertArrayHasKey('id_city', $model->errors);
        $this->assertArrayHasKey('id_country', $model->errors);
        $this->assertArrayHasKey('id_postcode', $model->errors);
        
        $model = new UsersModel(['scenario'=>UsersModel::UPDATE]);
        $model->attributes = [
            'id_name'=>1,
            'id_surname'=>1,
            'id_phone'=>1,
            'id_address'=>1,
            'id_city'=>1,
            'id_country'=>1,
            'id_postcode'=>1
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new UsersModel(['scenario'=>UsersModel::UPDATE_PASSW]);
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        $this->assertArrayHasKey('password', $model->errors);
        
        $model = new UsersModel(['scenario'=>UsersModel::UPDATE_PASSW]);
        $model->attributes = [
            'password'=>'password',
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
    
    /**
     * Тестирует метод UsersModel::tableName
     */
    public function testTableName()
    {
        $result = UsersModel::tableName();
        
        $this->assertSame('users', $result);
    }
    
    /**
     * Тестирует метод UsersModel::getEmail
     */
    public function testGetEmail()
    {
        $model = new UsersModel;
        $model->id_email = 1;
        
        $result = $model->email;
        
        $this->assertInstanceOf(EmailsModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::getName
     */
    public function testGetName()
    {
        $model = new UsersModel;
        $model->id_name = 1;
        
        $result = $model->name;
        
        $this->assertInstanceOf(NamesModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::getSurname
     */
    public function testGetSurname()
    {
        $model = new UsersModel;
        $model->id_surname = 1;
        
        $result = $model->surname;
        
        $this->assertInstanceOf(SurnamesModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::getPhone
     */
    public function testGetPhone()
    {
        $model = new UsersModel;
        $model->id_phone = 1;
        
        $result = $model->phone;
        
        $this->assertInstanceOf(PhonesModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::getAddress
     */
    public function testGetAddress()
    {
        $model = new UsersModel;
        $model->id_address = 1;
        
        $result = $model->address;
        
        $this->assertInstanceOf(AddressModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::getCity
     */
    public function testGetCity()
    {
        $model = new UsersModel;
        $model->id_city = 1;
        
        $result = $model->city;
        
        $this->assertInstanceOf(CitiesModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::getCountry
     */
    public function testGetCountry()
    {
        $model = new UsersModel;
        $model->id_country = 1;
        
        $result = $model->country;
        
        $this->assertInstanceOf(CountriesModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::getPostcode
     */
    public function testGetPostcode()
    {
        $model = new UsersModel;
        $model->id_postcode = 1;
        
        $result = $model->postcode;
        
        $this->assertInstanceOf(PostcodesModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::findIdentity
     */
    public function testFindIdentity()
    {
        $result = UsersModel::findIdentity(1);
        
        $this->assertInstanceOf(UsersModel::class, $result);
    }
    
    /**
     * Тестирует метод UsersModel::getId
     */
    public function testGetId()
    {
        $model = new UsersModel;
        $model->id = 1;
        
        $result = $model->getId();
        
        $this->assertEquals(1, $result);
    }
    
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
