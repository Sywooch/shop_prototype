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
                'users'=>'app\tests\source\fixtures\UsersFixture',
                'emails'=>'app\tests\source\fixtures\EmailsFixture',
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
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_AUTHENTICATION'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
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
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'id_email'=>$fixture['id_email'],
            'password'=>$fixture['password'],
            'name'=>$fixture['name'],
            'surname'=>$fixture['surname'],
            'id_phone'=>$fixture['id_phone'],
            'id_address'=>$fixture['id_address'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['id_email'], $model->id_email);
        $this->assertEquals($fixture['password'], $model->password);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['surname'], $model->surname);
        $this->assertEquals($fixture['id_phone'], $model->id_phone);
        $this->assertEquals($fixture['id_address'], $model->id_address);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'id_email'=>$fixture['id_email'],
            'password'=>$fixture['password'],
            'name'=>$fixture['name'],
            'surname'=>$fixture['surname'],
            'id_phone'=>$fixture['id_phone'],
            'id_address'=>$fixture['id_address'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['id_email'], $model->id_email);
        $this->assertEquals($fixture['password'], $model->password);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['surname'], $model->surname);
        $this->assertEquals($fixture['id_phone'], $model->id_phone);
        $this->assertEquals($fixture['id_address'], $model->id_address);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
        $model->attributes = [
            'password'=>$fixture['password'],
        ];
        
        $this->assertEquals($fixture['password'], $model->password);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('password', $model->errors));
        
        $fixture = self::$_dbClass->users['user_2'];
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
        $model->attributes = [
            'password'=>$fixture['password'],
        ];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод UsersModel::getEmails
     */
    public function testGetEmails()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_object($model->emails));
        $this->assertTrue($model->emails instanceof EmailsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта 
     * в процессе авторизации для 
     * - app\controllers\UserController
     */
    public function testGetOne()
    {
        $fixtureEmail = self::$_dbClass->emails['email_1'];
        
        $usersQuery = UsersModel::find();
        $usersQuery->extendSelect(['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address']);
        $usersQuery->innerJoin('emails', '[[users.id_email]]=[[emails.id]]');
        $usersQuery->where(['emails.email'=>$fixtureEmail['email']]);
        
        $queryRaw = clone $usersQuery;
        
        $expectedQuery = sprintf("SELECT `users`.`id`, `users`.`id_email`, `users`.`password`, `users`.`name`, `users`.`surname`, `users`.`id_phone`, `users`.`id_address` FROM `users` INNER JOIN `emails` ON `users`.`id_email`=`emails`.`id` WHERE `emails`.`email`='%s'", $fixtureEmail['email']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $usersQuery->one();
        
        $this->assertTrue($result instanceof UsersModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
