<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetPaginationWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\collections\PaginationInterface;
use app\controllers\ProductsListController;

/**
 * Тестирует класс GetPaginationWidgetConfigService
 */
class GetPaginationWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetPaginationWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetPaginationWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('paginationWidgetArray'));
    }
    
    /**
     * Тестирует метод GetPaginationWidgetConfigService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new GetPaginationWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetPaginationWidgetConfigService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return $name === \Yii::$app->params['pagePointer'] ? 2 : 0;
            }
        };
        
        $service = new GetPaginationWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
