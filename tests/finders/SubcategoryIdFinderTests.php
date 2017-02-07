<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SubcategoryIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\models\SubcategoryModel;

/**
 * Тестирует класс SubcategoryIdFinder
 */
class SubcategoryIdFinderTests extends TestCase
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
     * Тестирует свойства SubcategoryIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubcategoryIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SubcategoryIdFinder::setId
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetIdError()
    {
        $id = 'id';
        
        $widget = new SubcategoryIdFinder();
        $widget->setId($id);
    }
    
    /**
     * Тестирует метод SubcategoryIdFinder::setId
     */
    public function testSetId()
    {
        $id = 2;
        
        $widget = new SubcategoryIdFinder();
        $widget->setId($id);
        
        $reflection = new \ReflectionProperty($widget, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод SubcategoryIdFinder::find
     * если пуст SubcategoryIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $finder = new SubcategoryIdFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод SubcategoryIdFinder::find
     */
    public function testFind()
    {
        $finder = new SubcategoryIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(SubcategoryModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
