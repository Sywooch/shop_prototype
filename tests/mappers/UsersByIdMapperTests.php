<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\UsersByIdMapper;
use app\models\UsersModel;

/**
 * Тестирует класс app\mappers\UsersByIdMapper
 */
class UsersByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersByIdMapper::getGroup
     */
    public function testGetGroup()
    {
        $usersByIdMapper = new UsersByIdMapper([
            'tableName'=>'users',
            'fields'=>['id', 'login', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            'model'=>new UsersModel([
                'id'=>self::$_id,
            ]),
        ]);
        $objectUser = $usersByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($objectUser));
        $this->assertTrue($objectUser instanceof UsersModel);
        
        $this->assertFalse(empty($objectUser->id));
        $this->assertFalse(empty($objectUser->login));
        $this->assertFalse(empty($objectUser->name));
        $this->assertFalse(empty($objectUser->surname));
        $this->assertFalse(empty($objectUser->id_emails));
        $this->assertFalse(empty($objectUser->id_phones));
        $this->assertFalse(empty($objectUser->id_address));
        
        $this->assertEquals(self::$_login, $objectUser->login);
        $this->assertEquals(self::$_name, $objectUser->name);
        $this->assertEquals(self::$_surname, $objectUser->surname);
        $this->assertEquals(self::$_id, $objectUser->id_emails);
        $this->assertEquals(self::$_id, $objectUser->id_phones);
        $this->assertEquals(self::$_id, $objectUser->id_address);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
