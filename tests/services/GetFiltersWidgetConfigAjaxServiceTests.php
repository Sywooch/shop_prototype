<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetFiltersWidgetConfigAjaxService;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture,
    SubcategoryFixture};
use app\tests\DbManager;
use app\models\{CategoriesModel,
    SubcategoryModel};
use app\forms\FiltersForm;
use app\controllers\ProductsListController;

/**
 * Тестирует класс GetFiltersWidgetConfigAjaxService
 */
class GetFiltersWidgetConfigAjaxServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'colors'=>ColorsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'brands'=>BrandsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetFiltersWidgetConfigAjaxService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetFiltersWidgetConfigAjaxService::class);
        
        $this->assertTrue($reflection->hasProperty('filtersWidgetArray'));
    }
    
    /**
     * Тестирует метод GetFiltersWidgetConfigAjaxService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new GetFiltersWidgetConfigAjaxService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetFiltersWidgetConfigAjaxService::handle
     * если отсутствует $request[url]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: url
     */
    public function testHandleEmptyRequestUrl()
    {
        $request = [];
        
        $service = new GetFiltersWidgetConfigAjaxService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetFiltersWidgetConfigAjaxService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = ['url'=>'url'];
        
        $service = new GetFiltersWidgetConfigAjaxService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInstanceOf(FiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
