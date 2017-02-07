<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminProductsPaginationWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\collections\PaginationInterface;
use app\controllers\AdminController;

/**
 * Тестирует класс GetAdminProductsPaginationWidgetConfigService
 */
class GetAdminProductsPaginationWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetAdminProductsPaginationWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminProductsPaginationWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('paginationWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAdminProductsPaginationWidgetConfigService::get
     */
    public function testGet()
    {
        $_GET = [\Yii::$app->params['pagePointer']=>2];
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $service = new GetAdminProductsPaginationWidgetConfigService();
        $result = $service->get();
        
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
