<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAccountOrdersPaginationWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{PurchasesFixture,
    UsersFixture};
use app\collections\PaginationInterface;
use app\models\UsersModel;

/**
 * Тестирует класс GetAccountOrdersPaginationWidgetConfigService
 */
class GetAccountOrdersPaginationWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetAccountOrdersPaginationWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAccountOrdersPaginationWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('paginationWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAccountOrdersPaginationWidgetConfigService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new GetAccountOrdersPaginationWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetAccountOrdersPaginationWidgetConfigService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new GetAccountOrdersPaginationWidgetConfigService();
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
