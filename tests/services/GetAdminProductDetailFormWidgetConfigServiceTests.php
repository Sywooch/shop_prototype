<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminProductDetailFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    ProductsFixture,
    SizesFixture,
    SubcategoryFixture};
use app\models\ProductsModel;
use app\forms\AdminProductForm;

/**
 * Тестирует класс GetAdminProductDetailFormWidgetConfigService
 */
class GetAdminProductDetailFormWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'colors'=>ColorsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'brands'=>BrandsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetAdminProductDetailFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminProductDetailFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminProductDetailFormWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAdminProductDetailFormWidgetConfigService::handle
     * если передан пустой request
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testHandleNotExistsPage()
    {
        $request = [];
        
        $service = new GetAdminProductDetailFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetAdminProductDetailFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['id'=>1];
        
        $service = new GetAdminProductDetailFormWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('subcategory', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
        $this->assertInternalType('array', $result['categories']);
        $this->assertInternalType('array', $result['subcategory']);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInstanceOf(AdminProductForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
