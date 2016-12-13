<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategoriesFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\models\{CategoriesModel,
    SubcategoryModel};

/**
 * Тестирует класс CategoriesFinder
 */
class CategoriesFinderTests extends TestCase
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
     * Тестирует свойства CategoriesFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CategoriesFinder::find
     */
    public function testFind()
    {
        $finder = new CategoriesFinder();
        $categories = $finder->find();
        
        $this->assertInternalType('array', $categories);
        $this->assertNotEmpty($categories);
        foreach($categories as $category) {
            $this->assertInstanceOf(CategoriesModel::class, $category);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
