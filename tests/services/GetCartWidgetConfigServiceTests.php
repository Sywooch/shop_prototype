<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetProductBreadcrumbsWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс GetProductBreadcrumbsWidgetConfigService
 */
class GetProductBreadcrumbsWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetProductBreadcrumbsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetProductBreadcrumbsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('productBreadcrumbsWidgetArray'));
    }
    
    /**
     * Тестирует свойства GetProductBreadcrumbsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $seocode;
            public function get($name)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new GetProductBreadcrumbsWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('product', $result);
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
