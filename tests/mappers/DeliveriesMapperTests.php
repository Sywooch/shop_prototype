<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\DeliveriesMapper;
use app\models\DeliveriesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\DeliveriesMapper
 */
class DeliveriesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    private static $_price = 12.34;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{deliveries}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description, [[price]]=:price');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод DeliveriesMapper::getGroup
     */
    public function testGetGroup()
    {
        $deliveriesMapper = new DeliveriesMapper([
            'tableName'=>'deliveries',
            'fields'=>['id', 'name', 'description', 'price'],
        ]);
        $objectsArray = $deliveriesMapper->getGroup();
        
        $this->assertTrue(is_array($objectsArray));
        $this->assertFalse(empty($objectsArray));
        $this->assertTrue(is_object($objectsArray[0]));
        $this->assertTrue($objectsArray[0] instanceof DeliveriesModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($objectsArray[0], 'name'));
        $this->assertTrue(property_exists($objectsArray[0], 'description'));
        $this->assertTrue(property_exists($objectsArray[0], 'price'));
        
        $this->assertTrue(isset($objectsArray[0]->id));
        $this->assertTrue(isset($objectsArray[0]->name));
        $this->assertTrue(isset($objectsArray[0]->description));
        $this->assertTrue(isset($objectsArray[0]->price));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
