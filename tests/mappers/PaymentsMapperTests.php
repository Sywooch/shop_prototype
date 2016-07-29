<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\PaymentsMapper;
use app\models\PaymentsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\PaymentsMapper
 */
class PaymentsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{payments}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод PaymentsMapper::getGroup
     */
    public function testGetGroup()
    {
        $paymentsMapper = new PaymentsMapper([
            'tableName'=>'payments',
            'fields'=>['id', 'name', 'description'],
        ]);
        $objectsArray = $paymentsMapper->getGroup();
        
        $this->assertTrue(is_array($objectsArray));
        $this->assertFalse(empty($objectsArray));
        $this->assertTrue(is_object($objectsArray[0]));
        $this->assertTrue($objectsArray[0] instanceof PaymentsModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($objectsArray[0], 'name'));
        $this->assertTrue(property_exists($objectsArray[0], 'description'));
        
        $this->assertTrue(isset($objectsArray[0]->id));
        $this->assertTrue(isset($objectsArray[0]->name));
        $this->assertTrue(isset($objectsArray[0]->description));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
