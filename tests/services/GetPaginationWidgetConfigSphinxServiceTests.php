<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetPaginationWidgetConfigSphinxService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\collections\PaginationInterface;
use app\controllers\ProductsListController;

/**
 * Тестирует класс GetPaginationWidgetConfigSphinxService
 */
class GetPaginationWidgetConfigSphinxServiceTests extends TestCase
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
     * Тестирует свойства GetPaginationWidgetConfigSphinxService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetPaginationWidgetConfigSphinxService::class);
        
        $this->assertTrue($reflection->hasProperty('paginationWidgetArray'));
    }
    
    /**
     * Тестирует метод GetPaginationWidgetConfigSphinxService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new GetPaginationWidgetConfigSphinxService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetPaginationWidgetConfigSphinxService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 'пиджак';
            }
        };
        
        $service = new GetPaginationWidgetConfigSphinxService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
