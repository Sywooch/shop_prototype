<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\UsersInsertMapper;

/**
 * Тестирует класс app\mappers\UsersInsertMapper
 */
class UsersInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'somelogin';
    private static $_password = 'somepassword';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует метод UsersInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersInsertMapper = new UsersInsertMapper([
            'tableName'=>'users',
            'fields'=>['login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            'objectsArray'=>[
                new MockModel([
                    'login'=>self::$_login,
                    'password'=>password_hash(self::$_password, PASSWORD_DEFAULT),
                    'name'=>self::$_name,
                    'surname'=>self::$_surname,
                    'id_emails'=>self::$_id, 
                    'id_phones'=>self::$_id, 
                    'id_address'=>self::$_id
                ]),
            ]
        ]);
        $result = $usersInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users}} WHERE login=:login');
        $command->bindValue(':login', self::$_login);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('login', $result);
        $this->assertArrayHasKey('password', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('surname', $result);
        
        $this->assertEquals(self::$_login, $result['login']);
        $this->assertEquals(self::$_name, $result['name']);
        $this->assertEquals(self::$_surname, $result['surname']);
        $this->assertTrue(password_verify(self::$_password, $result['password']));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
