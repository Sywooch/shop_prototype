<?php

namespace app\tests\helpers;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\helpers\SubcategoryHelper;

/**
 * Тестирует класс app\helpers\SubcategoryHelper
 */
class SubcategoryHelperTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>'app\tests\sources\fixtures\CategoriesFixture',
                'subcategory'=>'app\tests\sources\fixtures\SubcategoryFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод SubcategoryHelper::allMap
     */
    public function testAllMap()
    {
        $fixtureCategory = self::$_dbClass->categories['category_1'];
        $fixture = self::$_dbClass->subcategory['subcategory_1'];
        
        $result = SubcategoryHelper::forCategoryMap($fixtureCategory['id'], 'id', 'name');
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(array_key_exists($fixture['id'], $result));
        $this->assertTrue(in_array($fixture['name'], $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
