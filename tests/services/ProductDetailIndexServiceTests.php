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
use app\forms\PurchaseForm;

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
        
        $this->assertArrayHasKey('productConfig', $result);
        $this->assertArrayHasKey('product', $result['productConfig']);
        $this->assertArrayHasKey('currency', $result['productConfig']);
        $this->assertArrayHasKey('view', $result['productConfig']);
        $this->assertInstanceOf(ProductsModel::class, $result['productConfig']['product']);
        $this->assertInstanceOf(CurrencyModel::class, $result['productConfig']['currency']);
        $this->assertInternalType('string', $result['productConfig']['view']);
        
        $this->assertArrayHasKey('breadcrumbsConfig', $result);
        $this->assertArrayHasKey('product', $result['breadcrumbsConfig']);
        $this->assertInstanceOf(ProductsModel::class, $result['breadcrumbsConfig']['product']);
        
        $this->assertArrayHasKey('toCartConfig', $result);
        $this->assertArrayHasKey('product', $result['toCartConfig']);
        $this->assertArrayHasKey('form', $result['toCartConfig']);
        $this->assertArrayHasKey('view', $result['toCartConfig']);
        $this->assertInstanceOf(ProductsModel::class, $result['toCartConfig']['product']);
        $this->assertInstanceOf(PurchaseForm::class, $result['toCartConfig']['form']);
        $this->assertInternalType('string', $result['toCartConfig']['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
