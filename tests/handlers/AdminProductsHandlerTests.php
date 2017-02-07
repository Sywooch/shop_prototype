<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminProductsHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    CurrencyFixture,
    ProductsFixture,
    SizesFixture};
use app\controllers\AdminController;

/**
 * Тестирует класс AdminProductsHandler
 */
class AdminProductsHandlerTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'brands'=>BrandsFixture::class,
                'categories'=>CategoriesFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства AdminProductsHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminProductsHandler::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AdminProductsHandler();
        $service->handle();
    }
    
    /**
     * Тестирует метод AdminProductsHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AdminProductsHandler();
        $result = $service->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminProductsFiltersWidgetConfig', $result);
        $this->assertArrayHasKey('adminProductsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('adminCsvProductsFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminProductsFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['adminProductsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['adminCsvProductsFormWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
