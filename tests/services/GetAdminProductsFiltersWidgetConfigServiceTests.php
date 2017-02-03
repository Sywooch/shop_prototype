<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminProductsFiltersWidgetConfigService;
use app\forms\AdminProductsFiltersForm;
use app\controllers\FiltersController;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    SizesFixture};

/**
 * Тестирует класс GetAdminProductsFiltersWidgetConfigService
 */
class GetAdminProductsFiltersWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class,
                'categories'=>CategoriesFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetAdminProductsFiltersWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminProductsFiltersWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminProductsFiltersWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAdminProductsFiltersWidgetConfigService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new FiltersController('filters', \Yii::$app);
        
        $service = new GetAdminProductsFiltersWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('activeStatuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInternalType('array', $result['categories']);
        $this->assertInternalType('array', $result['activeStatuses']);
        $this->assertInstanceOf(AdminProductsFiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterCLass()
    {
        self::$dbClass->unloadFixtures();
    }
}
