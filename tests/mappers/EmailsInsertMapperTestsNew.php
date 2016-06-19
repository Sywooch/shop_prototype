<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\EmailsInsertMapper;

/**
 * Тестирует класс app\mappers\EmailsInsertMapper
 */
class EmailsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует метод EmailsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $emailsInsertMapper = new EmailsInsertMapper([
            'tableName'=>'emails',
            'fields'=>['email'],
            'objectsArray'=>[
                new MockModel([
                    'email'=>self::$_email,
                ]),
            ],
        ]);
        $result = $emailsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{emails}} WHERE [[emails.email]]=:email');
        $command->bindValue(':email', self::$_email);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
        
        $this->assertEquals(self::$_email, $result['email']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
