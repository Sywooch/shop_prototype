<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\UsersAddressInsertMapper;

/**
 * Тестирует класс app\mappers\UsersAddressInsertMapper
 */
class UsersAddressInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{address}} SET [[id]]=:id, [[address]]=:address, [[city]]=:city, [[country]]=:country, [[postcode]]=:postcode');
        $command->bindValues([':id'=>self::$_id, ':address'=>self::$_address, ':city'=>self::$_city, ':country'=>self::$_country,  ':postcode'=>self::$_postcode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersAddressInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersEmailsInsertMapper = new UsersAddressInsertMapper([
            'tableName'=>'users_address',
            'fields'=>['id_users', 'id_address'],
            'DbArray'=>[
                ['id_users'=>self::$_id, 'id_address'=>self::$_id],
            ],
        ]);
        $result = $usersEmailsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users_address}}');
        $result = $command->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(1, count($result));
        
        $this->assertArrayHasKey('id_users', $result[0]);
        $this->assertArrayHasKey('id_address', $result[0]);
        
        $this->assertTrue(isset($result[0]['id_users']));
        $this->assertTrue(isset($result[0]['id_address']));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
