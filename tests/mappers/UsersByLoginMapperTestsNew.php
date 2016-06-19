<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\UsersByLoginMapper;
use app\models\UsersModel;

/**
 * Тестирует класс app\mappers\UsersByLoginMapper
 */
class UsersByLoginMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
    private static $_password = 'Somepassword';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[password]]=:password, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':password'=>self::$_password, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersByLoginMapper::getGroup
     */
    public function testGetGroup()
    {
        $usersByLoginMapper = new UsersByLoginMapper([
            'tableName'=>'users',
            'fields'=>['id', 'login', 'name', 'surname'],
            'model'=>new MockModel([
                'login'=>self::$_login,
            ]),
        ]);
        $objectUser = $usersByLoginMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($objectUser));
        $this->assertTrue($objectUser instanceof UsersModel);
        
        //$this->assertTrue(property_exists($objectUser, 'login'));
        //$this->assertTrue(property_exists($objectUser, 'password'));
        $this->assertTrue(property_exists($objectUser, 'name'));
        $this->assertTrue(property_exists($objectUser, 'surname'));
        
        $this->assertTrue(isset($objectUser->id));
        $this->assertTrue(isset($objectUser->login));
        $this->assertTrue(isset($objectUser->name));
        $this->assertTrue(isset($objectUser->surname));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
