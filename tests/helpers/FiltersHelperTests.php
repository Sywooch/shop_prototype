<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\models\FiltersModel;
use app\helpers\{FiltersHelper,
    MappersHelper};

/**
 * Тестирует класс app\helpers\FiltersHelper
 */
class FiltersHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_colors = ['gray', 'black'];
    private static $_sizes = ['46', '54'];
    private static $_brands = ['Adidas', 'Reebok'];
    private static $_sortingField = 'date';
    private static $_sortingType = 'DESC';
    private static $_categories = 'menswear';
    private static $_categories2 = 'shoes';
    private static $_subcategory = 'pants';
    private static $_subcategory2 = 'sneakers';
    private static $_search = 'воротник';
    private static $_getActive = false;
    private static $_getNotActive = true;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categories]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategory]);
        $command->execute();
        
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
            'ProductsModel'=>[
                'id_categories'=>self::$_id,
                'id_subcategory'=>self::$_id,
            ],
        ];
        
        \Yii::$app->request->getBodyParams();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
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
        $this->assertTrue(empty(\Yii::$app->filters->getActive));
        $this->assertEquals(self::$_getActive, \Yii::$app->filters->getActive);
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
        $this->assertEquals(self::$_getNotActive, \Yii::$app->filters->getNotActive);
        
        $result = FiltersHelper::cleanFilters();
        
        $this->assertTrue($result);
        
        $this->assertTrue(empty(\Yii::$app->filters->colors));
        $this->assertTrue(empty(\Yii::$app->filters->sizes));
        $this->assertTrue(empty(\Yii::$app->filters->brands));
        $this->assertTrue(empty(\Yii::$app->filters->sortingField));
        $this->assertTrue(empty(\Yii::$app->filters->sortingType));
        $this->assertFalse(empty(\Yii::$app->filters->getActive));
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
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
     * Тестирует метод FiltersHelper::addFiltersAdminCategories
     */
    public function testAddFiltersAdminCategories()
    {
        \Yii::$app->filters->colors = self::$_colors;
        \Yii::$app->filters->sizes = self::$_sizes;
        \Yii::$app->filters->brands = self::$_brands;
        \Yii::$app->filters->sortingField = self::$_sortingField;
        \Yii::$app->filters->sortingType = self::$_sortingType;
        \Yii::$app->filters->categories = self::$_categories;
        \Yii::$app->filters->subcategory = self::$_subcategory;
        \Yii::$app->filters->search = self::$_search;
        \Yii::$app->filters->getActive = self::$_getActive;
        \Yii::$app->filters->getNotActive = self::$_getNotActive;
        
        $result = FiltersHelper::addFiltersAdminCategories();
        
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
        
        \Yii::$app->filters->categories = self::$_categories2;
        \Yii::$app->filters->subcategory = self::$_subcategory2;
        
        $result = FiltersHelper::addFiltersAdminCategories();
        
        $this->assertTrue($result);
        
        $this->assertTrue(empty(\Yii::$app->filters->colors));
        $this->assertTrue(empty(\Yii::$app->filters->sizes));
        $this->assertTrue(empty(\Yii::$app->filters->brands));
        $this->assertTrue(empty(\Yii::$app->filters->sortingField));
        $this->assertTrue(empty(\Yii::$app->filters->sortingType));
        $this->assertFalse(empty(\Yii::$app->filters->getActive));
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
        $this->assertFalse(empty(\Yii::$app->filters->search));
        $this->assertEquals(self::$_search, \Yii::$app->filters->search);
        $this->assertFalse(empty(\Yii::$app->filters->categories));
        $this->assertEquals(self::$_categories, \Yii::$app->filters->categories);
        $this->assertFalse(empty(\Yii::$app->filters->subcategory));
        $this->assertEquals(self::$_subcategory, \Yii::$app->filters->subcategory);
    }
    
    /**
     * Тестирует метод FiltersHelper::addFiltersConvert
     */
    public function testAddFiltersConvert()
    {
        FiltersHelper::cleanFilters();
        FiltersHelper::cleanOtherFilters();
        
        $this->assertTrue(empty(\Yii::$app->filters->colors));
        $this->assertTrue(empty(\Yii::$app->filters->sizes));
        $this->assertTrue(empty(\Yii::$app->filters->brands));
        $this->assertTrue(empty(\Yii::$app->filters->sortingField));
        $this->assertTrue(empty(\Yii::$app->filters->sortingType));
        $this->assertFalse(empty(\Yii::$app->filters->getActive));
        $this->assertFalse(empty(\Yii::$app->filters->getNotActive));
        $this->assertTrue(empty(\Yii::$app->filters->categories));
        $this->assertTrue(empty(\Yii::$app->filters->subcategory));
        $this->assertTrue(empty(\Yii::$app->filters->search));
        
        FiltersHelper::addFiltersConvert();
        
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
        $this->assertFalse(empty(\Yii::$app->filters->categories));
        $this->assertEquals(self::$_categories, \Yii::$app->filters->categories);
        $this->assertFalse(empty(\Yii::$app->filters->subcategory));
        $this->assertEquals(self::$_subcategory, \Yii::$app->filters->subcategory);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
