<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AdminDeliveriesFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\DeliveriesFixture;
use app\models\DeliveriesModel;

/**
 * Тестирует класс AdminDeliveriesFinder
 */
class AdminDeliveriesFinderTests extends TestCase
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
     * Тестирует свойства AdminDeliveriesFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminDeliveriesFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminDeliveriesFinder::find
     */
    public function testFind()
    {
        $finder = new AdminDeliveriesFinder();
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
