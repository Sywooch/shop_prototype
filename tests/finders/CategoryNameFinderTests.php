<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategoryNameFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\models\CategoriesModel;

/**
 * Тестирует класс CategoryNameFinder
 */
class CategoryNameFinderTests extends TestCase
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
        $this->finder = new CategoryNameFinder();
    }
    
    /**
     * Тестирует свойства CategoryNameFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoryNameFinder::class);
        
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CategoryNameFinder::setName
     */
    public function testSetName()
    {
        $this->finder->setName('Name');
        
        $reflection = new \ReflectionProperty($this->finder, 'name');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CategoryNameFinder::find
     * если пуст CategoryNameFinder::name
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: name
     */
    public function testFindEmptyName()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод CategoryNameFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->categories['category_1']['name']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CategoriesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
