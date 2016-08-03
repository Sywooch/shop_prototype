<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\UsersInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\UsersInsertMapper
 */
class UsersInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_id_emails = 15;
    private static $_password = 'somepassword';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id_emails, ':email'=>self::$_email]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод UsersInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersInsertMapper = new UsersInsertMapper([
            'tableName'=>'users',
            'fields'=>['password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            'objectsArray'=>[
                new MockModel([
                    'password'=>password_hash(self::$_password, PASSWORD_DEFAULT),
                    'name'=>self::$_name,
                    'surname'=>self::$_surname,
                    'id_emails'=>self::$_id_emails, 
                    'id_phones'=>self::$_id, 
                    'id_address'=>self::$_id
                ]),
            ]
        ]);
        $result = $usersInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users}} WHERE id_emails=:id_emails');
        $command->bindValue(':id_emails', self::$_id_emails);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('id_emails', $result);
        $this->assertArrayHasKey('password', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('surname', $result);
        
        $this->assertEquals(self::$_id_emails, $result['id_emails']);
        $this->assertEquals(self::$_name, $result['name']);
        $this->assertEquals(self::$_surname, $result['surname']);
        $this->assertTrue(password_verify(self::$_password, $result['password']));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
