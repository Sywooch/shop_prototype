<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizeIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\SizesFixture;
use app\models\SizesModel;

/**
 * Тестирует класс SizeIdFinder
 */
class SizeIdFinderTests extends TestCase
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
        $this->finder = new SizeIdFinder();
    }
    
    /**
     * Тестирует свойства SizeIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizeIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SizeIdFinder::setId
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
     * Тестирует метод SizeIdFinder::find
     * если пуст SizeIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод SizeIdFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 1);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(SizesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
