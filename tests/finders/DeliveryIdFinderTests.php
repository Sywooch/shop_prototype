<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\DeliveryIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\DeliveriesFixture;
use app\models\DeliveriesModel;

/**
 * Тестирует класс DeliveryIdFinder
 */
class DeliveryIdFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства DeliveryIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(DeliveryIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод DeliveryIdFinder::setId
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetIdError()
    {
        $id = null;
        
        $widget = new DeliveryIdFinder();
        $widget->setId($id);
    }
    
    /**
     * Тестирует метод DeliveryIdFinder::setId
     */
    public function testSetId()
    {
        $id = 2;
        
        $widget = new DeliveryIdFinder();
        $widget->setId($id);
        
        $reflection = new \ReflectionProperty($widget, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод DeliveryIdFinder::find
     * если пуст DeliveryIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $finder = new DeliveryIdFinder();
        $deliveries = $finder->find();
    }
    
    /**
     * Тестирует метод DeliveryIdFinder::find
     */
    public function testFind()
    {
        $finder = new DeliveryIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(DeliveriesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
