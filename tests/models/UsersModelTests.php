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
