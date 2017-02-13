<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\ProductDetailIndexRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CommentsFixture,
    CurrencyFixture,
    ProductsFixture};
use app\controllers\ProductDetailController;
use app\models\{CurrencyInterface,
    CurrencyModel,
    ProductsModel};
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс ProductDetailIndexRequestHandler
 */
class ProductDetailIndexRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'comments'=>CommentsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new ProductDetailController('detail', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new ProductDetailIndexRequestHandler();
    }
    
    /**
     * Тестирует свойства ProductDetailIndexRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductDetailIndexRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::productDetailWidgetConfig
     */
    public function testProductDetailWidgetConfig()
    {
        $productsModel = new class() extends ProductsModel {};
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'productDetailWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsModel, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::orderFormWidgetConfig
     */
    public function testOrderFormWidgetConfig()
    {
        $productsModel = new class() extends ProductsModel {};
        $purchaseForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'orderFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsModel, $purchaseForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::productBreadcrumbsWidget
     */
    public function testProductBreadcrumbsWidget()
    {
        $productsModel = new class() extends ProductsModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'productBreadcrumbsWidget');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('product', $result);
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::seeAlsoWidgetSimilarConfig
     */
    public function testSeeAlsoWidgetSimilarConfig()
    {
        $similarArray = [new class() extends ProductsModel {}];
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'seeAlsoWidgetSimilarConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $similarArray, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['products']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::seeAlsoWidgetRelatedConfig
     */
    public function testSeeAlsoWidgetRelatedConfig()
    {
        $relatedArray = [new class() extends ProductsModel {}];
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'seeAlsoWidgetRelatedConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $relatedArray, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['products']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::commentsWidgetConfig
     */
    public function testCommentsWidgetConfig()
    {
        $commentsArray = [new class() extends ProductsModel {
            public $id = 1;
        }];
        
        $reflection = new \ReflectionMethod($this->handler, 'commentsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $commentsArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('comments', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['comments']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::сommentFormWidgetConfig
     */
    public function testCommentFormWidgetConfig()
    {
        $commentForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'сommentFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $commentForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $seocode;
            public function get($name=null, $defaultValue=null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setAccessible(true);
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $result = $this->handler->handle($request);
        
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
    
    /**
     * Тестирует метод ProductDetailIndexRequestHandler::handle
     * если товар не найден
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmpty()
    {
        $request = new class() {
            public $seocode;
            public function get($name=null, $defaultValue=null)
            {
                return 'nothing';
            }
        };
        
        $this->handler->handle($request);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
