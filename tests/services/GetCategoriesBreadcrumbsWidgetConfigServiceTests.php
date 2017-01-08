<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCategoriesBreadcrumbsWidgetConfigService;
use app\tests\sources\fixtures\{CategoriesFixture,
    SubcategoryFixture};
use app\tests\DbManager;
use app\models\{CategoriesModel,
    SubcategoryModel};

/**
 * Тестирует класс GetCategoriesBreadcrumbsWidgetConfigService
 */
class GetCategoriesBreadcrumbsWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetCategoriesBreadcrumbsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCategoriesBreadcrumbsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('categoriesBreadcrumbsWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCategoriesBreadcrumbsWidgetConfigService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new GetCategoriesBreadcrumbsWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetCategoriesBreadcrumbsWidgetConfigService::handle
     */
    public function testGetBreadcrumbsArray()
    {
        $request = new class() {
            public $category;
            public $subcategory;
            public function get($name = null, $defaultValue = null)
            {
                if ($name === 'category') {
                    return $this->category;
                }
                if ($name === 'subcategory') {
                    return $this->subcategory;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'category');
        $reflection->setValue($request, self::$dbClass->categories['category_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'subcategory');
        $reflection->setValue($request, self::$dbClass->subcategory['subcategory_1']['seocode']);
        
        $service = new GetCategoriesBreadcrumbsWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('category', $result);
        $this->assertArrayHasKey('subcategory', $result);
        $this->assertInstanceOf(CategoriesModel::class, $result['category']);
        $this->assertInstanceOf(SubcategoryModel::class, $result['subcategory']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
