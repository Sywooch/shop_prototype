<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{AddressModel,
    CitiesModel,
    CountriesModel,
    EmailsModel,
    NamesModel,
    PhonesModel,
    PostcodesModel,
    SurnamesModel,
    UsersModel};

/**
 * Тестирует класс app\models\UsersModel
 */
class UsersModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'users'=>'app\tests\sources\fixtures\UsersFixture',
                'emails'=>'app\tests\sources\fixtures\EmailsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\UsersModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\UsersModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_AUTHENTICATION'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_REGISTRATION'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
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
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
        $model->attributes = [
            'password'=>$fixture['password'],
        ];
        
        $this->assertEquals($fixture['password'], $model->password);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_REGISTRATION]);
        $model->attributes = [
            'password'=>$fixture['password'],
        ];
        
        $this->assertEquals($fixture['password'], $model->password);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_ORDER]);
        $model->attributes = [
            'id'=>$fixture['id'],
            'password'=>$fixture['password'],
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['password'], $model->password);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->users['user_2'];
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('password', $model->errors));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
        $model->attributes = [
            'password'=>$fixture['password'],
        ];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_REGISTRATION]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('password', $model->errors));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_REGISTRATION]);
        $model->attributes = [
            'password'=>$fixture['password'],
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
        $this->assertEquals(0, $model->id_name);
        $this->assertEquals(0, $model->id_surname);
        $this->assertEquals(0, $model->id_phone);
        $this->assertEquals(0, $model->id_address);
    }
    
    /**
     * Тестирует поля, возвращаемые Model::attributes
     */
    public function testToArray()
    {
        $fixture = self::$_dbClass->users['user_2'];
        
        $model = new UsersModel();
        $model->id = $fixture['id'];
        $model->id_email = $fixture['id_email'];
        $model->password = $fixture['password'];
        $model->id_name = $fixture['id_name'];
        $model->id_surname = $fixture['id_surname'];
        $model->id_phone = $fixture['id_phone'];
        $model->id_address = $fixture['id_address']; 
        $model->id_address = $fixture['id_city'];
        $model->id_address = $fixture['id_country']; 
        $model->id_address = $fixture['id_postcode']; 
        
        $result = $model->toArray();
        
        $this->assertEquals(9, count($result));
        $this->assertTrue(array_key_exists('id', $result));
        $this->assertTrue(array_key_exists('id_email', $result));
        $this->assertTrue(array_key_exists('id_name', $result));
        $this->assertTrue(array_key_exists('id_surname', $result));
        $this->assertTrue(array_key_exists('id_phone', $result));
        $this->assertTrue(array_key_exists('id_address', $result));
        $this->assertTrue(array_key_exists('id_city', $result));
        $this->assertTrue(array_key_exists('id_country', $result));
        $this->assertTrue(array_key_exists('id_postcode', $result));
        
        $result = $model->toArray([], ['password']);
        
        $this->assertEquals(10, count($result));
        $this->assertTrue(array_key_exists('id', $result));
        $this->assertTrue(array_key_exists('id_email', $result));
        $this->assertTrue(array_key_exists('password', $result));
        $this->assertTrue(array_key_exists('id_name', $result));
        $this->assertTrue(array_key_exists('id_surname', $result));
        $this->assertTrue(array_key_exists('id_phone', $result));
        $this->assertTrue(array_key_exists('id_address', $result));
        $this->assertTrue(array_key_exists('id_city', $result));
        $this->assertTrue(array_key_exists('id_country', $result));
        $this->assertTrue(array_key_exists('id_postcode', $result));
    }
    
    /**
     * Тестирует метод UsersModel::getEmail
     */
    public function testGetEmail()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->email instanceof EmailsModel);
    }
    
    /**
     * Тестирует метод UsersModel::getName
     */
    public function testGetName()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->name instanceof NamesModel);
    }
    
    /**
     * Тестирует метод UsersModel::getSurname
     */
    public function testGetSurname()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->surname instanceof SurnamesModel);
    }
    
    /**
     * Тестирует метод UsersModel::getPhone
     */
    public function testGetPhone()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->phone instanceof PhonesModel);
    }
    
    /**
     * Тестирует метод UsersModel::getAddress
     */
    public function testGetAddress()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->address instanceof AddressModel);
    }
    
    /**
     * Тестирует метод UsersModel::getCity
     */
    public function testGetCity()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->city instanceof CitiesModel);
    }
    
    /**
     * Тестирует метод UsersModel::getCountry
     */
    public function testGetCountry()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->country instanceof CountriesModel);
    }
    
    /**
     * Тестирует метод UsersModel::getPostcode
     */
    public function testGetPostcode()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->postcode instanceof PostcodesModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $usersQuery = UsersModel::find();
        $usersQuery->extendSelect(['id', 'id_email', 'password', 'id_name', 'id_surname', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode']);
        
        $queryRaw = clone $usersQuery;
        
        $expectedQuery = "SELECT `users`.`id`, `users`.`id_email`, `users`.`password`, `users`.`id_name`, `users`.`id_surname`, `users`.`id_phone`, `users`.`id_address`, `users`.`id_city`, `users`.`id_country`, `users`.`id_postcode` FROM `users`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $usersQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof UsersModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта 
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $usersQuery = UsersModel::find();
        $usersQuery->extendSelect(['id', 'id_email', 'password', 'id_name', 'id_surname', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode']);
        $usersQuery->where(['users.id_email'=>$fixture['id_email']]);
        
        $queryRaw = clone $usersQuery;
        
        $expectedQuery = sprintf("SELECT `users`.`id`, `users`.`id_email`, `users`.`password`, `users`.`id_name`, `users`.`id_surname`, `users`.`id_phone`, `users`.`id_address`, `users`.`id_city`, `users`.`id_country`, `users`.`id_postcode` FROM `users` WHERE `users`.`id_email`=%d", $fixture['id_email']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $usersQuery->one();
        
        $this->assertTrue($result instanceof UsersModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->users['user_1'];
        $fixture2 = self::$_dbClass->users['user_1'];
        
        $usersQuery = UsersModel::find();
        $usersQuery->extendSelect(['id', 'id_name']);
        $usersArray = $usersQuery->allMap('id', 'id_name');
        
        $this->assertFalse(empty($usersArray));
        $this->assertTrue(array_key_exists($fixture['id'], $usersArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $usersArray));
        $this->assertTrue(in_array($fixture['id_name'], $usersArray));
        $this->assertTrue(in_array($fixture2['id_name'], $usersArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
