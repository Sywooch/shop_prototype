<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\DeliveriesFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\DeliveriesFixture;
use app\models\DeliveriesModel;

/**
 * Тестирует класс DeliveriesFinder
 */
class DeliveriesFinderTests extends TestCase
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
     * Тестирует свойства DeliveriesFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(DeliveriesFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод DeliveriesFinder::find
     */
    public function testFind()
    {
        $finder = new DeliveriesFinder();
        $deliveries = $finder->find();
        
        $this->assertInternalType('array', $deliveries);
        $this->assertNotEmpty($deliveries);
        foreach($deliveries as $delivery) {
            $this->assertInstanceOf(DeliveriesModel::class, $delivery);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
