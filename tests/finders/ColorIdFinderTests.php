<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;
use app\models\ColorsModel;

/**
 * Тестирует класс ColorIdFinder
 */
class ColorIdFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new ColorIdFinder();
    }
    
    /**
     * Тестирует свойства ColorIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ColorIdFinder::setId
     */
    public function testSetId()
    {
        $this->finder->setId(2);
        
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод ColorIdFinder::find
     * если пуст ColorIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод ColorIdFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 1);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(ColorsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
