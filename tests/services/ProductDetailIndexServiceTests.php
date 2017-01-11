<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ProductDetailIndexService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CommentsFixture,
    CurrencyFixture,
    ProductsFixture};
use app\controllers\ProductDetailController;

/**
 * Тестирует класс ProductDetailIndexService
 */
class ProductDetailIndexServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'comments'=>CommentsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductDetailIndexService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductDetailIndexService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'handle');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductDetailController('product-detail', \Yii::$app);
        
        $request = new class() {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new ProductDetailIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('productDetailWidgetConfig', $result);
        $this->assertArrayHasKey('purchaseFormWidgetConfig', $result);
        $this->assertArrayHasKey('productBreadcrumbsWidget', $result);
        $this->assertArrayHasKey('seeAlsoWidgetSimilarConfig', $result);
        $this->assertArrayHasKey('seeAlsoWidgetRelatedConfig', $result);
        $this->assertArrayHasKey('commentsWidgetConfig', $result);
        $this->assertArrayHasKey('сommentFormWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['productDetailWidgetConfig']);
        $this->assertInternalType('array', $result['purchaseFormWidgetConfig']);
        $this->assertInternalType('array', $result['productBreadcrumbsWidget']);
        $this->assertInternalType('array', $result['seeAlsoWidgetSimilarConfig']);
        $this->assertInternalType('array', $result['seeAlsoWidgetRelatedConfig']);
        $this->assertInternalType('array', $result['commentsWidgetConfig']);
        $this->assertInternalType('array', $result['сommentFormWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
