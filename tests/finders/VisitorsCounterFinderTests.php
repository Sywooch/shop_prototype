<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\VisitorsCounterFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\VisitorsCounterFixture;
use app\models\VisitorsCounterModel;

/**
 * Тестирует класс VisitorsCounterFinder
 */
class VisitorsCounterFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'visitors_counter'=>VisitorsCounterFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства VisitorsCounterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(VisitorsCounterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод VisitorsCounterFinder::find
     */
    public function testFind()
    {
        $finder = new VisitorsCounterFinder();
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(VisitorsCounterModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
