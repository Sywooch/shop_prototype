<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetSeeAlsoWidgetSimilarConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\models\CurrencyModel;

/**
 * Тестирует класс GetSeeAlsoWidgetSimilarConfigService
 */
class GetSeeAlsoWidgetSimilarConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetSeeAlsoWidgetSimilarConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetSeeAlsoWidgetSimilarConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('seeAlsoWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetSeeAlsoWidgetSimilarConfigService::handle
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
        
        $service = new GetSeeAlsoWidgetSimilarConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
