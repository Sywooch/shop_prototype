<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\DeliveriesByIdMapper;
use app\models\DeliveriesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\DeliveriesByIdMapper
 */
class DeliveriesByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    private static $_price = 23.12;
    
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
     * Тестирует метод DeliveriesByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $deliveriesByIdMapper = new DeliveriesByIdMapper([
            'tableName'=>'deliveries',
            'fields'=>['id', 'name', 'description', 'price'],
            'model'=>new DeliveriesModel([
                'id'=>self::$_id,
            ]),
        ]);
        $deliveriesModel = $deliveriesByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($deliveriesModel));
        $this->assertTrue($deliveriesModel instanceof DeliveriesModel);
        
        //$this->assertTrue(property_exists($deliveriesModel, 'id'));
        $this->assertTrue(property_exists($deliveriesModel, 'name'));
        $this->assertTrue(property_exists($deliveriesModel, 'description'));
        $this->assertTrue(property_exists($deliveriesModel, 'price'));
        
        $this->assertTrue(isset($deliveriesModel->id));
        $this->assertTrue(isset($deliveriesModel->name));
        $this->assertTrue(isset($deliveriesModel->description));
        $this->assertTrue(isset($deliveriesModel->price));
        
        $this->assertEquals(self::$_name, $deliveriesModel->name);
        $this->assertEquals(self::$_description, $deliveriesModel->description);
        $this->assertEquals(self::$_price, $deliveriesModel->price);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
