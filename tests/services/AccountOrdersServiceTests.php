<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountOrdersService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\controllers\FiltersController;
use app\models\UsersModel;

/**
 * Тестирует класс AccountOrdersService
 */
class AccountOrdersServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AccountOrdersService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountOrdersService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountOrdersService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AccountOrdersService();
        $service->handle();
    }
    
    /**
     * Тестирует метод AccountOrdersService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new FiltersController('filters', \Yii::$app);
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AccountOrdersService();
        $result = $service->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('оrdersFiltersWidgetConfig', $result);
        $this->assertArrayHasKey('accountOrdersWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['оrdersFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['accountOrdersWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
