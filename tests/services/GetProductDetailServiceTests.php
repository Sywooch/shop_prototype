<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetProductDetailService;
use app\tests\sources\fixtures\ProductsFixture;
use app\tests\DbManager;
use app\models\ProductsModel;

/**
 * Тестирует класс GetProductDetailService
 */
class GetProductDetailServiceTests extends TestCase
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
     * Тестирует свойства GetProductDetailService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetProductDetailService::class);
        
        $this->assertTrue($reflection->hasProperty('productsModel'));
    }
    
    /**
     * Тестирует метод GetProductDetailService::handle
     * если не передан $request
     * @expectedException ErrorException
     */
    public function testHandleNotRequest()
    {
        $service = new GetProductDetailService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetProductDetailService::handle
     * если $request пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: seocode
     */
    public function testHandleEmptyRequest()
    {
        $request = new class() {
            public function get($name)
            {
                return null;
            }
        };
        
        $service = new GetProductDetailService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetProductDetailService::handle
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
        
        $service = new GetProductDetailService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
