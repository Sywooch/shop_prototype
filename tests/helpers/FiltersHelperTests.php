<?php

namespace app\tests\helpers;

use app\models\FiltersModel;
use app\helpers\FiltersHelper;

/**
 * Тестирует класс app\helpers\FiltersHelper
 */
class FiltersHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_colors = ['gray', 'black'];
    private static $_sizes = ['46', '54'];
    private static $_brands = ['Adidas', 'Reebok'];
    private static $_sortingField = 'date';
    private static $_sortingType = 'DESC';
    private static $_categories = 'menswear';
    private static $_subcategory = 'pants';
    private static $_search = 'воротник';
    private static $_getActive = false;
    private static $_getNotActive = true;
    
    /**
     * Тестирует метод FiltersHelper::addFilters
     */
    public function testAddFilters()
    {
        $this->assertTrue(empty(\Yii::$app->filters->colors));
        $this->assertTrue(empty(\Yii::$app->filters->sizes));
        $this->assertTrue(empty(\Yii::$app->filters->brands));
        $this->assertTrue(empty(\Yii::$app->filters->sortingField));
        $this->assertTrue(empty(\Yii::$app->filters->sortingType));
        $this->assertTrue(empty(\Yii::$app->filters->categories));
        $this->assertTrue(empty(\Yii::$app->filters->subcategory));
        $this->assertTrue(empty(\Yii::$app->filters->search));
        $this->assertTrue(\Yii::$app->filters->getActive);
        $this->assertTrue(\Yii::$app->filters->getNotActive);
        
        $_POST = [
            '_method'=>'POST',
            'FiltersModel'=>[
                'colors'=>self::$_colors,
                'sizes'=>self::$_sizes,
                'brands'=>self::$_brands,
                'sortingField'=>self::$_sortingField,
                'sortingType'=>self::$_sortingType,
                'categories'=>self::$_categories,
                'subcategory'=>self::$_subcategory,
                'search'=>self::$_search,
                'getActive'=>self::$_getActive,
                'getNotActive'=>self::$_getNotActive,
            ],
        ];
        
        \Yii::$app->request->getBodyParams();
        
        $result = FiltersHelper::addFilters();
        
        $this->assertTrue($result);
        
        $this->assertTrue(is_array(\Yii::$app->filters->colors));
        $this->assertFalse(empty(\Yii::$app->filters->colors));
        $this->assertEquals(self::$_colors, \Yii::$app->filters->colors);
        $this->assertTrue(is_array(\Yii::$app->filters->sizes));
        $this->assertFalse(empty(\Yii::$app->filters->sizes));
        $this->assertEquals(self::$_sizes, \Yii::$app->filters->sizes);
        $this->assertTrue(is_array(\Yii::$app->filters->brands));
        $this->assertFalse(empty(\Yii::$app->filters->brands));
        $this->assertEquals(self::$_brands, \Yii::$app->filters->brands);
        $this->assertFalse(empty(\Yii::$app->filters->sortingField));
        $this->assertEquals(self::$_sortingField, \Yii::$app->filters->sortingField);
        $this->assertFalse(empty(\Yii::$app->filters->sortingType));
        $this->assertEquals(self::$_sortingType, \Yii::$app->filters->sortingType);
        $this->assertFalse(empty(\Yii::$app->filters->categories));
        $this->assertEquals(self::$_categories, \Yii::$app->filters->categories);
        $this->assertFalse(empty(\Yii::$app->filters->subcategory));
        $this->assertEquals(self::$_subcategory, \Yii::$app->filters->subcategory);
        $this->assertFalse(empty(\Yii::$app->filters->search));
        $this->assertEquals(self::$_search, \Yii::$app->filters->search);
        $this->assertTrue(empty(\Yii::$app->filters->getActive));
        $this->assertEquals(self::$_getActive, \Yii::$app->filters->getActive);
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
        $this->assertEquals(self::$_getNotActive, \Yii::$app->filters->getNotActive);
    }
    
    /**
     * Тестирует метод FiltersHelper::cleanFilters
     */
    public function testCleanFilters()
    {
        $this->assertTrue(is_array(\Yii::$app->filters->colors));
        $this->assertFalse(empty(\Yii::$app->filters->colors));
        $this->assertEquals(self::$_colors, \Yii::$app->filters->colors);
        $this->assertTrue(is_array(\Yii::$app->filters->sizes));
        $this->assertFalse(empty(\Yii::$app->filters->sizes));
        $this->assertEquals(self::$_sizes, \Yii::$app->filters->sizes);
        $this->assertTrue(is_array(\Yii::$app->filters->brands));
        $this->assertFalse(empty(\Yii::$app->filters->brands));
        $this->assertEquals(self::$_brands, \Yii::$app->filters->brands);
        $this->assertFalse(empty(\Yii::$app->filters->sortingField));
        $this->assertEquals(self::$_sortingField, \Yii::$app->filters->sortingField);
        $this->assertFalse(empty(\Yii::$app->filters->sortingType));
        $this->assertEquals(self::$_sortingType, \Yii::$app->filters->sortingType);
        
        $result = FiltersHelper::cleanFilters();
        
        $this->assertTrue($result);
        
        $this->assertTrue(empty(\Yii::$app->filters->colors));
        $this->assertTrue(empty(\Yii::$app->filters->sizes));
        $this->assertTrue(empty(\Yii::$app->filters->brands));
        $this->assertTrue(empty(\Yii::$app->filters->sortingField));
        $this->assertTrue(empty(\Yii::$app->filters->sortingType));
    }
    
    /**
     * Тестирует метод FiltersHelper::cleanOtherFilters
     */
    public function testCleanOtherFilters()
    {
        $this->assertFalse(empty(\Yii::$app->filters->categories));
        $this->assertEquals(self::$_categories, \Yii::$app->filters->categories);
        $this->assertFalse(empty(\Yii::$app->filters->subcategory));
        $this->assertEquals(self::$_subcategory, \Yii::$app->filters->subcategory);
        $this->assertFalse(empty(\Yii::$app->filters->search));
        $this->assertEquals(self::$_search, \Yii::$app->filters->search);
        
        $result = FiltersHelper::cleanOtherFilters();
        
        $this->assertTrue($result);
        
        $this->assertTrue(empty(\Yii::$app->filters->categories));
        $this->assertTrue(empty(\Yii::$app->filters->subcategory));
        $this->assertTrue(empty(\Yii::$app->filters->search));
    }
    
    /**
     * Тестирует метод FiltersHelper::cleanAdminFilters
     */
    public function testCleanAdminFilters()
    {
        FiltersHelper::addFilters();
        
        $this->assertFalse(empty(\Yii::$app->filters->categories));
        $this->assertEquals(self::$_categories, \Yii::$app->filters->categories);
        $this->assertFalse(empty(\Yii::$app->filters->subcategory));
        $this->assertEquals(self::$_subcategory, \Yii::$app->filters->subcategory);
        
        $this->assertTrue(empty(\Yii::$app->filters->getActive));
        $this->assertEquals(self::$_getActive, \Yii::$app->filters->getActive);
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
        $this->assertEquals(self::$_getNotActive, \Yii::$app->filters->getNotActive);
        
        $result = FiltersHelper::cleanAdminFilters();
        
        $this->assertTrue($result);
        
        $this->assertTrue(empty(\Yii::$app->filters->categories));
        $this->assertTrue(empty(\Yii::$app->filters->subcategory));
        $this->assertFalse(empty(\Yii::$app->filters->getActive));
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
    }
    
    /**
     * Тестирует метод FiltersHelper::addFiltersAdmin
     */
    public function testAddFiltersAdmin()
    {
        FiltersHelper::cleanFilters();
        FiltersHelper::cleanOtherFilters();
        FiltersHelper::cleanAdminFilters();
        
        $this->assertTrue(empty(\Yii::$app->filters->colors));
        $this->assertTrue(empty(\Yii::$app->filters->sizes));
        $this->assertTrue(empty(\Yii::$app->filters->brands));
        $this->assertTrue(empty(\Yii::$app->filters->sortingField));
        $this->assertTrue(empty(\Yii::$app->filters->sortingType));
        $this->assertTrue(empty(\Yii::$app->filters->categories));
        $this->assertTrue(empty(\Yii::$app->filters->subcategory));
        $this->assertTrue(empty(\Yii::$app->filters->search));
        $this->assertFalse(empty(\Yii::$app->filters->getActive));
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
        
        $result = FiltersHelper::addFiltersAdmin();
        
        $this->assertTrue($result);
        
        $this->assertFalse(empty(\Yii::$app->filters->categories));
        $this->assertEquals(self::$_categories, \Yii::$app->filters->categories);
        $this->assertFalse(empty(\Yii::$app->filters->subcategory));
        $this->assertEquals(self::$_subcategory, \Yii::$app->filters->subcategory);
        $this->assertTrue(empty(\Yii::$app->filters->active));
        $this->assertTrue(empty(\Yii::$app->filters->getActive));
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
    }
}
