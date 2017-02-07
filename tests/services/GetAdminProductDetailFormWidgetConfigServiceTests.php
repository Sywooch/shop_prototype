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
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('adminProductDetailFormWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAdminProductDetailFormWidgetConfigService::setId
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetIdError()
    {
        $service = new GetAdminProductDetailFormWidgetConfigService();
        $service->setId('id');
    }
    
    /**
     * Тестирует метод GetAdminProductDetailFormWidgetConfigService::setId
     */
    public function testSetId()
    {
        $service = new GetAdminProductDetailFormWidgetConfigService();
        $service->setId(23);
        
        $reflection = new \ReflectionProperty($service, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals(23, $result);
    }
    
    /**
     * Тестирует метод GetAdminProductDetailFormWidgetConfigService::get
     * если отсутствует id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testGetEmptyId()
    {
        $service = new GetAdminProductDetailFormWidgetConfigService();
        $service->get();
    }
    
    /**
     * Тестирует метод  GetAdminProductDetailFormWidgetConfigService::get
     */
    public function testGet()
    {
        $service = new GetAdminProductDetailFormWidgetConfigService();
        
        $reflection = new \ReflectionProperty($service, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($service, 1);
        
        $result = $service->get();
        
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
