<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SubcategoryIdCategoryFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\models\SubcategoryModel;

/**
 * Тестирует класс SubcategoryIdCategoryFinder
 */
class SubcategoryIdCategoryFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SubcategoryIdCategoryFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubcategoryIdCategoryFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_category'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SubcategoryIdCategoryFinder::setId_category
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetId_categoryError()
    {
        $id_category = null;
        
        $widget = new SubcategoryIdCategoryFinder();
        $widget->setId_category($id_category);
    }
    
    /**
     * Тестирует метод SubcategoryIdCategoryFinder::setId_category
     */
    public function testSetId_category()
    {
        $id_category = 2;
        
        $widget = new SubcategoryIdCategoryFinder();
        $widget->setId_category($id_category);
        
        $reflection = new \ReflectionProperty($widget, 'id_category');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод SubcategoryIdCategoryFinder::find
     * если пуст SubcategoryIdCategoryFinder::id_category
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_category
     */
    public function testFindEmptyId_category()
    {
        $finder = new SubcategoryIdCategoryFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод SubcategoryIdCategoryFinder::find
     */
    public function testFind()
    {
        $finder = new SubcategoryIdCategoryFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id_category');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SubcategoryModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
