<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ProductDetailIndexService;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\tests\DbManager;
use app\controllers\ProductDetailController;
use app\models\{CurrencyModel,
    ProductsModel};
use app\forms\{CommentForm,
    PurchaseForm};

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
        
        $this->assertTrue($reflection->hasProperty('productsModel'));
        $this->assertTrue($reflection->hasProperty('productArray'));
        $this->assertTrue($reflection->hasProperty('purchaseFormArray'));
        $this->assertTrue($reflection->hasProperty('breadcrumbsArray'));
        $this->assertTrue($reflection->hasProperty('similarArray'));
        $this->assertTrue($reflection->hasProperty('relatedArray'));
        $this->assertTrue($reflection->hasProperty('commentsArray'));
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getProductsModel
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetProductsModelEmptyRequest()
    {
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getProductsModel
     * если данные отсутствуют
     * @expectedException \yii\web\NotFoundHttpException
     */
    public function testGetProductsModel404()
    {
        $request = ['seocode'=>'nothing'];
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getProductsModel
     */
    public function testGetProductsModel()
    {
        $request = ['seocode'=>self::$dbClass->products['product_1']['seocode']];
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getProductArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetProductArrayEmptyRequest()
    {
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getProductArray
     */
    public function testGetProductArray()
    {
        $request = ['seocode'=>self::$dbClass->products['product_1']['seocode']];
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('productConfig', $result);
        $this->assertArrayHasKey('product', $result['productConfig']);
        $this->assertArrayHasKey('currency', $result['productConfig']);
        $this->assertArrayHasKey('view', $result['productConfig']);
        $this->assertInstanceOf(ProductsModel::class, $result['productConfig']['product']);
        $this->assertInstanceOf(CurrencyModel::class, $result['productConfig']['currency']);
        $this->assertInternalType('string', $result['productConfig']['view']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getPurchaseFormArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetPurchaseFormArrayEmptyRequest()
    {
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getPurchaseFormArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getPurchaseFormArray
     */
    public function testGetPurchaseFormArray()
    {
        $request = ['seocode'=>self::$dbClass->products['product_1']['seocode']];
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getPurchaseFormArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('toCartConfig', $result);
        $this->assertArrayHasKey('product', $result['toCartConfig']);
        $this->assertArrayHasKey('form', $result['toCartConfig']);
        $this->assertArrayHasKey('view', $result['toCartConfig']);
        $this->assertInstanceOf(ProductsModel::class, $result['toCartConfig']['product']);
        $this->assertInstanceOf(PurchaseForm::class, $result['toCartConfig']['form']);
        $this->assertInternalType('string', $result['toCartConfig']['view']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getBreadcrumbsArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetBreadcrumbsArrayEmptyRequest()
    {
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getBreadcrumbsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getBreadcrumbsArray
     */
    public function testGetBreadcrumbsArray()
    {
        $request = ['seocode'=>self::$dbClass->products['product_1']['seocode']];
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getBreadcrumbsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('product', $result['breadcrumbsConfig']);
        $this->assertInstanceOf(ProductsModel::class, $result['breadcrumbsConfig']['product']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getSimilarArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetSimilarArrayEmptyRequest()
    {
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getSimilarArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getSimilarArray
     */
    public function testGetSimilarArray()
    {
        $request = ['seocode'=>self::$dbClass->products['product_1']['seocode']];
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getSimilarArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('similarConfig', $result);
        $this->assertArrayHasKey('products', $result['similarConfig']);
        $this->assertArrayHasKey('currency', $result['similarConfig']);
        $this->assertArrayHasKey('header', $result['similarConfig']);
        $this->assertArrayHasKey('view', $result['similarConfig']);
        $this->assertInternalType('array', $result['similarConfig']['products']);
        foreach ($result['similarConfig']['products'] as $item) {
            $this->assertInstanceOf(ProductsModel::class, $item);
        }
        $this->assertInstanceOf(CurrencyModel::class, $result['similarConfig']['currency']);
        $this->assertInternalType('string', $result['similarConfig']['header']);
        $this->assertInternalType('string', $result['similarConfig']['view']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getRelatedArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetRelatedArrayEmptyRequest()
    {
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getRelatedArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getRelatedArray
     */
    public function testGetRelatedArray()
    {
        $request = ['seocode'=>self::$dbClass->products['product_1']['seocode']];
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getRelatedArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('relatedConfig', $result);
        $this->assertArrayHasKey('products', $result['relatedConfig']);
        $this->assertArrayHasKey('currency', $result['relatedConfig']);
        $this->assertArrayHasKey('header', $result['relatedConfig']);
        $this->assertArrayHasKey('view', $result['relatedConfig']);
        $this->assertInternalType('array', $result['relatedConfig']['products']);
        foreach ($result['relatedConfig']['products'] as $item) {
            $this->assertInstanceOf(ProductsModel::class, $item);
        }
        $this->assertInstanceOf(CurrencyModel::class, $result['relatedConfig']['currency']);
        $this->assertInternalType('string', $result['relatedConfig']['header']);
        $this->assertInternalType('string', $result['relatedConfig']['view']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getCommentsArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetCommentsArrayEmptyRequest()
    {
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getCommentsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }
    
    /**
     * Тестирует метод ProductDetailIndexService::getCommentsArray
     */
    public function testGetCommentsArray()
    {
        $request = ['seocode'=>self::$dbClass->products['product_1']['seocode']];
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getCommentsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('commentsConfig', $result);
        $this->assertArrayHasKey('comments', $result['commentsConfig']);
        $this->assertArrayHasKey('form', $result['commentsConfig']);
        $this->assertArrayHasKey('view', $result['commentsConfig']);
        $this->assertInternalType('array', $result['commentsConfig']['comments']);
        $this->assertInstanceOf(CommentForm::class, $result['commentsConfig']['form']);
        $this->assertInternalType('string', $result['commentsConfig']['view']);
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
        
        $request = ['seocode'=>self::$dbClass->products['product_1']];
        
        $service = new ProductDetailIndexService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('productConfig', $result);
        $this->assertArrayHasKey('toCartConfig', $result);
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('similarConfig', $result);
        $this->assertArrayHasKey('relatedConfig', $result);
        $this->assertArrayHasKey('commentsConfig', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
