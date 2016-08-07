<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\EmailsMailingListInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\EmailsMailingListInsertMapper
 */
class EmailsMailingListInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_email = 'some@some.com';
    private static $_name = 'some name';
    private static $_description = 'some description';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{mailing_list}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод EmailsMailingListInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $emailsMailingListInsertMapper = new EmailsMailingListInsertMapper([
            'tableName'=>'emails_mailing_list',
            'fields'=>['id_email', 'id_mailing_list'],
            'objectsArray'=>[
                new MockModel([
                    'id_email'=>self::$_id,
                    'id_mailing_list'=>self::$_id,
                ]),
            ],
        ]);
        $result = $emailsMailingListInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}} WHERE [[emails_mailing_list.id_email]]=:id_email AND [[emails_mailing_list.id_mailing_list]]=:id_mailing_list');
        $command->bindValues([':id_email'=>self::$_id, ':id_mailing_list'=>self::$_id]);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id_email', $result);
        $this->assertArrayHasKey('id_mailing_list', $result);
        
        $this->assertEquals(self::$_id, $result['id_email']);
        $this->assertEquals(self::$_id, $result['id_mailing_list']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
