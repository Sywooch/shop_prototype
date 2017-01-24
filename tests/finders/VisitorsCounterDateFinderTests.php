<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\VisitorsCounterDateFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\VisitorsCounterFixture;
use app\models\VisitorsCounterModel;

/**
 * Тестирует класс VisitorsCounterDateFinder
 */
class VisitorsCounterDateFinderTests extends TestCase
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
     * Тестирует свойства VisitorsCounterDateFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(VisitorsCounterDateFinder::class);
        
        $this->assertTrue($reflection->hasProperty('date'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод VisitorsCounterDateFinder::find
     * если пуст VisitorsCounterDateFinder::date
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: date
     */
    public function testFindEmptyDate()
    {
        $finder = new VisitorsCounterDateFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод VisitorsCounterDateFinder::find
     */
    public function testFind()
    {
        $finder = new VisitorsCounterDateFinder();
        
        $reflection = new \ReflectionProperty($finder, 'date');
        $reflection->setValue($finder, self::$dbClass->visitors_counter['visitors_counter_1']['date']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(VisitorsCounterModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
