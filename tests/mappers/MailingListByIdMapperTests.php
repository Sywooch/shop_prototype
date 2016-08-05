<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\MailingListByIdMapper;
use app\models\MailingListModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\MailingListByIdMapper
 */
class MailingListByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
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
     * Тестирует метод MailingListByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $mailingListByIdMapper = new MailingListByIdMapper([
            'tableName'=>'mailing_list',
            'fields'=>['id', 'name', 'description'],
            'model'=>new MailingListModel([
                'id'=>self::$_id,
            ]),
        ]);
        $mailingListModel = $mailingListByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($mailingListModel));
        $this->assertTrue($mailingListModel instanceof MailingListModel);
        
        $this->assertTrue(property_exists($mailingListModel, 'id'));
        $this->assertTrue(property_exists($mailingListModel, 'name'));
        $this->assertTrue(property_exists($mailingListModel, 'description'));
        
        $this->assertFalse(empty($mailingListModel->id));
        $this->assertFalse(empty($mailingListModel->name));
        $this->assertFalse(empty($mailingListModel->description));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
