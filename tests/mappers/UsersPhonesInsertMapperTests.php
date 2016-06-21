<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\UsersPhonesInsertMapper;

/**
 * Тестирует класс app\mappers\UsersPhonesInsertMapper
 */
class UsersPhonesInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_phone = '+380504568910';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} SET [[id]]=:id, [[phone]]=:phone');
        $command->bindValues([':id'=>self::$_id, ':phone'=>self::$_phone]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersPhonesInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersPhonesInsertMapper = new UsersPhonesInsertMapper([
            'tableName'=>'users_phones',
            'fields'=>['id_users', 'id_phones'],
            'DbArray'=>[
                ['id_users'=>self::$_id, 'id_phones'=>self::$_id],
            ],
        ]);
        $result = $usersPhonesInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users_phones}}');
        $result = $command->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(1, count($result));
        
        $this->assertArrayHasKey('id_users', $result[0]);
        $this->assertArrayHasKey('id_phones', $result[0]);
        
        $this->assertTrue(isset($result[0]['id_users']));
        $this->assertTrue(isset($result[0]['id_phones']));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
