<?php

namespace app\tests\mappers;

use app\mappers\EmailsInsertMapper;
use app\tests\DbManager;
use app\models\UsersModel;
use app\models\EmailsModel;

/**
 * Тестирует класс app\mappers\EmailsInsertMapper
 */
class EmailsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод EmailsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $emailArray = ['email'=>'test@test.com'];
        $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $emailsModel->attributes = $emailArray;
        
        $emailsInsertMapper = new EmailsInsertMapper([
            'tableName'=>'emails',
            'fields'=>['email'],
            'objectsArray'=>[$emailsModel],
        ]);
        $result = $emailsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{emails}} WHERE [[emails.email]]=:email');
        $command->bindValue(':email', $emailArray['email']);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
        
        $this->assertEquals($emailArray['email'], $result['email']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
