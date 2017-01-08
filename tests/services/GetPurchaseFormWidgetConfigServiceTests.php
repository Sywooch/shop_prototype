<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetPurchaseFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\forms\PurchaseForm;
use app\models\ProductsModel;

/**
 * Тестирует класс GetPurchaseFormWidgetConfigService
 */
class GetPurchaseFormWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetPurchaseFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetPurchaseFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('purchaseFormWidgetArray'));
    }
    
    /**
     * Тестирует метод GetPurchaseFormWidgetConfigService::handle
     * если не найден товар
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmptyProduct()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 'nothing';
            }
        };
        
        $service = new GetPurchaseFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetPurchaseFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new GetPurchaseFormWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
        $this->assertInstanceOf(PurchaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
