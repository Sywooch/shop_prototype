<?php

namespace app\tests\factories;

use app\factories\UsersOneObjectsFactory;
use app\tests\DbManager;
use app\mappers\UsersByLoginMapper;
use app\queries\UsersByLoginQueryCreator;
use app\models\UsersModel;

/**
 * Тестирует класс app\factories\UsersOneObjectsFactory
 */
class UsersOneObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод UsersOneObjectsFactory::getOne()
     */
    public function testGetOne()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[users.login]]=:login');
        $command->bindValue(':login', 'user');
        $command->execute();
        
        $modelUserModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $modelUserModel->attributes = ['login'=>'user'];
        
        $usersByLoginMapper = new UsersByLoginMapper([
            'tableName'=>'users',
            'fields'=>['id', 'login', 'name'],
            'model'=>$modelUserModel,
        ]);
        
        $this->assertEmpty($usersByLoginMapper->DbArray);
        $this->assertFalse(isset($usersByLoginMapper->objectsOne));
        
        $usersByLoginMapper->visit(new UsersByLoginQueryCreator());
        
        $command = \Yii::$app->db->createCommand($usersByLoginMapper->query);
        $command->bindValue(':login', 'user');
        $usersByLoginMapper->DbArray = $command->queryOne();
        
        $this->assertFalse(empty($usersByLoginMapper->DbArray));
        
        $usersByLoginMapper->visit(new UsersOneObjectsFactory());
        
        $this->assertTrue(is_object($usersByLoginMapper->objectsOne));
        $this->assertTrue($usersByLoginMapper->objectsOne instanceof UsersModel);
        
        //$this->assertTrue(property_exists($usersByLoginMapper->objectsOne, 'id'));
        $this->assertTrue(isset($usersByLoginMapper->objectsOne->id));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
