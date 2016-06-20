<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\UsersAddressByUsersAddressMapper;
use app\models\UsersAddressModel;

/**
 * Тестирует класс app\mappers\UsersAddressByUsersAddressMapper
 */
class UsersAddressByUsersAddressMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
    private static $_password = 'Somepassword';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_address = 'Some address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = '12656';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[password]]=:password, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':password'=>self::$_password, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{address}} SET [[id]]=:id, [[address]]=:address, [[city]]=:city, [[country]]=:country, [[postcode]]=:postcode');
        $command->bindValues([':id'=>self::$_id, ':address'=>self::$_address, ':city'=>self::$_city, ':country'=>self::$_country, ':postcode'=>self::$_postcode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users_address}} SET [[id_users]]=:id_users, [[id_address]]=:id_address');
        $command->bindValues([':id_users'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersAddressByUsersAddressMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $usersAddressByUsersAddressMapper = new UsersAddressByUsersAddressMapper([
            'tableName'=>'users_address',
            'fields'=>['id_users', 'id_address'],
            'params'=>[
                ':id_users'=>self::$_id, 
                ':id_address'=>self::$_id,
            ]
        ]);
        $object = $usersAddressByUsersAddressMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($object));
        $this->assertTrue($object instanceof UsersAddressModel);
        
        $this->assertTrue(property_exists($object, 'id_users'));
        $this->assertTrue(property_exists($object, 'id_address'));
        
        $this->assertTrue(isset($object->id_users));
        $this->assertTrue(isset($object->id_address));
        
        $this->assertEquals(self::$_id, $object->id_users);
        $this->assertEquals(self::$_id, $object->id_address);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
