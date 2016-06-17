<?php

namespace app\tests\factories;

use app\factories\UsersObjectsFactory;
use app\tests\DbManager;
use app\mappers\UsersByLoginMapper;
use app\queries\UsersByLoginQueryCreator;
use app\models\UsersModel;

/**
 * Тестирует класс app\factories\UsersObjectsFactory
 */
class UsersObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод UsersObjectsFactory::getOne()
     */
    public function testGetOne()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[users.login]]=:login');
        $command->bindValue(':login', 'user');
        $command->execute();
        
        $modelUserModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $modelUserModel->attributes = ['login'=>'user', 'name'=>'Some', 'surname'=>'Some'];
        
        $usersByLoginMapper = new UsersByLoginMapper([
            'tableName'=>'users',
            'fields'=>['login', 'name', 'surname'],
            'model'=>$modelUserModel,
        ]);
        
        $this->assertEmpty($usersByLoginMapper->DbArray);
        $this->assertEmpty($usersByLoginMapper->objectsArray);
        
        $usersByLoginMapper->visit(new UsersByLoginQueryCreator());
        
        $command = \Yii::$app->db->createCommand($usersByLoginMapper->query);
        $command->bindValue(':login', 'user');
        $usersByLoginMapper->DbArray = $command->queryAll();
        
        $this->assertFalse(empty($usersByLoginMapper->DbArray));
        
        $usersByLoginMapper->visit(new UsersObjectsFactory());
        
        $this->assertFalse(empty($usersByLoginMapper->objectsArray));
        $this->assertTrue(is_object($usersByLoginMapper->objectsArray[0]));
        $this->assertTrue($usersByLoginMapper->objectsArray[0] instanceof UsersModel);
        
        $this->assertTrue(property_exists($usersByLoginMapper->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($usersByLoginMapper->objectsArray[0], 'surname'));
        
        $this->assertTrue(isset($usersByLoginMapper->objectsArray[0]->id));
        $this->assertTrue(isset($usersByLoginMapper->objectsArray[0]->login));
        $this->assertTrue(isset($usersByLoginMapper->objectsArray[0]->password));
        $this->assertTrue(isset($usersByLoginMapper->objectsArray[0]->name));
        $this->assertTrue(isset($usersByLoginMapper->objectsArray[0]->name));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
