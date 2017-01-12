<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCartWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\collections\PurchasesCollectionInterface;
use app\models\CurrencyModel;
use app\forms\PurchaseForm;

/**
 * Тестирует класс GetCartWidgetConfigService
 */
class GetCartWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetCartWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCartWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('cartWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCartWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetCartWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('updateForm', $result);
        $this->assertArrayHasKey('deleteForm', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(PurchaseForm::class, $result['updateForm']);
        $this->assertInstanceOf(PurchaseForm::class, $result['deleteForm']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
