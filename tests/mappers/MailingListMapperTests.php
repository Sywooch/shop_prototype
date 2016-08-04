<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\MailingListMapper;
use app\models\MailingListModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\MailingListMapper
 */
class MailingListMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'some name';
    private static $_description = 'some description';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{mailing_list}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод MailingListMapper::getGroup
     */
    public function testGetGroup()
    {
        $mailingListMapper = new MailingListMapper([
            'tableName'=>'mailing_list',
            'fields'=>['id', 'name', 'description'],
        ]);
        $mailingList = $mailingListMapper->getGroup();
        
        $this->assertTrue(is_array($mailingList));
        $this->assertFalse(empty($mailingList));
        $this->assertTrue(is_object($mailingList[0]));
        $this->assertTrue($mailingList[0] instanceof MailingListModel);
        
        $this->assertTrue(property_exists($mailingList[0], 'id'));
        $this->assertTrue(property_exists($mailingList[0], 'name'));
        $this->assertTrue(property_exists($mailingList[0], 'description'));
        
        $this->assertFalse(empty($mailingList[0]->id));
        $this->assertFalse(empty($mailingList[0]->name));
        $this->assertFalse(empty($mailingList[0]->description));
        
        $this->assertEquals(self::$_id, $mailingList[0]->id);
        $this->assertEquals(self::$_name, $mailingList[0]->name);
        $this->assertEquals(self::$_description, $mailingList[0]->description);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
