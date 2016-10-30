<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{EmailsModel,
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
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('surname', $model->attributes));
        $this->assertTrue(array_key_exists('id_phone', $model->attributes));
        $this->assertTrue(array_key_exists('id_address', $model->attributes));
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
            'name'=>$fixture['name'],
            'surname'=>$fixture['surname'],
            'id_phone'=>$fixture['id_phone'],
            'id_address'=>$fixture['id_address'], 
        ];
        
        $this->assertEquals($fixture['password'], $model->password);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['surname'], $model->surname);
        $this->assertEquals($fixture['id_phone'], $model->id_phone);
        $this->assertEquals($fixture['id_address'], $model->id_address);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_ORDER]);
        $model->attributes = [
            'name'=>$fixture['name'],
            'surname'=>$fixture['surname'],
        ];
        
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['surname'], $model->surname);
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
        
        $this->assertEquals(0, count($model->errors));
        $this->assertEquals('', $model->name);
        $this->assertEquals('', $model->surname);
        $this->assertEquals(0, $model->id_phone);
        $this->assertEquals(0, $model->id_address);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('surname', $model->errors));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_ORDER]);
        $model->attributes = [
            'name'=>$fixture['name'],
            'surname'=>$fixture['surname'],
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует поля, возвращаемые Model::toArray()
     */
    public function testToArray()
    {
        $fixture = self::$_dbClass->users['user_2'];
        
        $model = new UsersModel();
        $model->id = $fixture['id'];
        $model->id_email = $fixture['id_email'];
        $model->password = $fixture['password'];
        $model->name = $fixture['name'];
        $model->surname = $fixture['surname'];
        $model->id_phone = $fixture['id_phone'];
        $model->id_address = $fixture['id_address']; 
        
        $result = $model->toArray();
        
        $this->assertEquals(5, count($result));
        $this->assertTrue(array_key_exists('id_email', $result));
        $this->assertTrue(array_key_exists('name', $result));
        $this->assertTrue(array_key_exists('surname', $result));
        $this->assertTrue(array_key_exists('id_phone', $result));
        $this->assertTrue(array_key_exists('id_address', $result));
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
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $usersQuery = UsersModel::find();
        $usersQuery->extendSelect(['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address']);
        
        $queryRaw = clone $usersQuery;
        
        $expectedQuery = "SELECT `users`.`id`, `users`.`id_email`, `users`.`password`, `users`.`name`, `users`.`surname`, `users`.`id_phone`, `users`.`id_address` FROM `users`";
        
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
        $usersQuery->extendSelect(['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address']);
        $usersQuery->where(['users.id_email'=>$fixture['id_email']]);
        
        $queryRaw = clone $usersQuery;
        
        $expectedQuery = sprintf("SELECT `users`.`id`, `users`.`id_email`, `users`.`password`, `users`.`name`, `users`.`surname`, `users`.`id_phone`, `users`.`id_address` FROM `users` WHERE `users`.`id_email`=%d", $fixture['id_email']);
        
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
        $usersQuery->extendSelect(['id', 'name']);
        $usersArray = $usersQuery->allMap('id', 'name');
        
        $this->assertFalse(empty($usersArray));
        $this->assertTrue(array_key_exists($fixture['id'], $usersArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $usersArray));
        $this->assertTrue(in_array($fixture['name'], $usersArray));
        $this->assertTrue(in_array($fixture2['name'], $usersArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
