<?php

namespace app\tests\mappers;

use app\mappers\UsersByLoginMapper;
use app\tests\DbManager;
use app\models\UsersModel;

/**
 * Тестирует класс app\mappers\UsersByLoginMapper
 */
class UsersByLoginMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод UsersByLoginMapper::getOne
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
        $objectUser = $usersByLoginMapper->getOne();
        
        $this->assertTrue(is_object($objectUser));
        $this->assertTrue($objectUser instanceof UsersModel);
        
        $this->assertTrue(property_exists($objectUser, 'login'));
        
        $this->assertTrue(isset($objectUser->id));
        $this->assertTrue(isset($objectUser->login));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
