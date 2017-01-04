<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ProductDetailIndexService;
use app\tests\sources\fixtures\{CommentsFixture,
    CurrencyFixture,
    ProductsFixture};
use app\tests\DbManager;
use app\controllers\ProductDetailController;
use app\models\{CurrencyModel,
    ProductsModel};
use app\forms\{CommentForm,
    PurchaseForm};
use yii\web\Request;

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
        
        $this->assertTrue($reflection->hasProperty('productArray'));
        $this->assertTrue($reflection->hasProperty('purchaseFormArray'));
        $this->assertTrue($reflection->hasProperty('breadcrumbsArray'));
        $this->assertTrue($reflection->hasProperty('similarArray'));
        $this->assertTrue($reflection->hasProperty('relatedArray'));
        $this->assertTrue($reflection->hasProperty('commentsArray'));
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
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['view']);
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
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getPurchaseFormArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
        $this->assertInstanceOf(PurchaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
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
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getBreadcrumbsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('product', $result);
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
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
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getSimilarArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['products']);
        foreach ($result['products'] as $item) {
            $this->assertInstanceOf(ProductsModel::class, $item);
        }
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['view']);
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
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getRelatedArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['products']);
        foreach ($result['products'] as $item) {
            $this->assertInstanceOf(ProductsModel::class, $item);
        }
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['view']);
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
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new ProductDetailIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getCommentsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('comments', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['comments']);
        $this->assertInstanceOf(CommentForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
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
    /*public function testHandle()
    {
        \Yii::$app->controller = new ProductDetailController('product-detail', \Yii::$app);
        
        $request = new class() extends Request {
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
    }*/
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
