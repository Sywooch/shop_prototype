<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategorySeocodeFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\models\CategoriesModel;

/**
 * Тестирует класс CategorySeocodeFinder
 */
class CategorySeocodeFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CategorySeocodeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategorySeocodeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('seocode'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::setSeocode
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSeocodeError()
    {
        $seocode = null;
        
        $widget = new CategorySeocodeFinder();
        $widget->setSeocode($seocode);
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::setSeocode
     */
    public function testSetSeocode()
    {
        $seocode = 'seocode';
        
        $widget = new CategorySeocodeFinder();
        $widget->setSeocode($seocode);
        
        $reflection = new \ReflectionProperty($widget, 'seocode');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::find
     * если пуст CategorySeocodeFinder::seocode
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: seocode
     */
    public function testFindEmptySeocode()
    {
        $finder = new CategorySeocodeFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CategorySeocodeFinder::find
     */
    public function testFind()
    {
        $fixture = self::$dbClass->categories['category_1'];
        
        $finder = new CategorySeocodeFinder();
        
        $reflection = new \ReflectionProperty($finder, 'seocode');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $fixture['seocode']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(CategoriesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
