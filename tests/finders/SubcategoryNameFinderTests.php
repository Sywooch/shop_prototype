<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SubcategoryNameFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\models\SubcategoryModel;

/**
 * Тестирует класс SubcategoryNameFinder
 */
class SubcategoryNameFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new SubcategoryNameFinder();
    }
    
    /**
     * Тестирует свойства SubcategoryNameFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubcategoryNameFinder::class);
        
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SubcategoryNameFinder::setName
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
     * Тестирует метод SubcategoryNameFinder::find
     * если пуст SubcategoryNameFinder::name
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: name
     */
    public function testFindEmptyName()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод SubcategoryNameFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->subcategory['subcategory_1']['name']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(SubcategoryModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
