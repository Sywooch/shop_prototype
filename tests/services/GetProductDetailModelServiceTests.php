<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetProductDetailModelService;
use app\tests\sources\fixtures\ProductsFixture;
use app\tests\DbManager;
use app\models\ProductsModel;

/**
 * Тестирует класс GetProductDetailModelService
 */
class GetProductDetailModelServiceTests extends TestCase
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
     * Тестирует свойства GetProductDetailModelService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetProductDetailModelService::class);
        
        $this->assertTrue($reflection->hasProperty('productsModel'));
    }
    
    /**
     * Тестирует метод GetProductDetailModelService::handle
     * если не передан $request
     * @expectedException ErrorException
     */
    public function testHandleNotRequest()
    {
        $service = new GetProductDetailModelService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetProductDetailModelService::handle
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
        
        $service = new GetProductDetailModelService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetProductDetailModelService::handle
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
        
        $service = new GetProductDetailModelService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
