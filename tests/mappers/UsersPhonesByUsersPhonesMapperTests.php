<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\UsersPhonesByUsersPhonesMapper;
use app\models\UsersPhonesModel;

/**
 * Тестирует класс app\mappers\UsersPhonesByUsersPhonesMapper
 */
class UsersPhonesByUsersPhonesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
    private static $_password = 'Somepassword';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_phone = '+380954568978';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[password]]=:password, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':password'=>self::$_password, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} SET [[id]]=:id, [[phone]]=:phone');
        $command->bindValues([':id'=>self::$_id, ':phone'=>self::$_phone]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users_phones}} SET [[id_users]]=:id_users, [[id_phones]]=:id_phones');
        $command->bindValues([':id_users'=>self::$_id, ':id_phones'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersPhonesByUsersPhonesMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $usersPhonesByUsersPhonesMapper = new UsersPhonesByUsersPhonesMapper([
            'tableName'=>'users_phones',
            'fields'=>['id_users', 'id_phones'],
            'params'=>[
                ':id_users'=>self::$_id, 
                ':id_phones'=>self::$_id,
            ]
        ]);
        $object = $usersPhonesByUsersPhonesMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($object));
        $this->assertTrue($object instanceof UsersPhonesModel);
        
        $this->assertTrue(property_exists($object, 'id_users'));
        $this->assertTrue(property_exists($object, 'id_phones'));
        
        $this->assertTrue(isset($object->id_users));
        $this->assertTrue(isset($object->id_phones));
        
        $this->assertEquals(self::$_id, $object->id_users);
        $this->assertEquals(self::$_id, $object->id_phones);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
