<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizeSizeFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\SizesFixture;
use app\models\SizesModel;

/**
 * Тестирует класс SizeSizeFinder
 */
class SizeSizeFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new SizeSizeFinder();
    }
    
    /**
     * Тестирует свойства SizeSizeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizeSizeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('size'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SizeSizeFinder::setSize
     */
    public function testSetSize()
    {
        $this->finder->setSize('size');
        
        $reflection = new \ReflectionProperty($this->finder, 'size');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод SizeSizeFinder::find
     * если пуст SizeSizeFinder::size
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: size
     */
    public function testFindEmptySize()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод SizeSizeFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'size');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->sizes['size_1']['size']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(SizesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
