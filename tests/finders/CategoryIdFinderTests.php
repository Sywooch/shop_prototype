<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategoryIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\models\CategoriesModel;

/**
 * Тестирует класс CategoryIdFinder
 */
class CategoryIdFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new CategoryIdFinder();
    }
    
    /**
     * Тестирует свойства CategoryIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoryIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CategoryIdFinder::setId
     */
    public function testSetId()
    {
        $this->finder->setId(15);
        
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод CategoryIdFinder::find
     * если пуст CategoryIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод CategoryIdFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 1);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CategoriesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
