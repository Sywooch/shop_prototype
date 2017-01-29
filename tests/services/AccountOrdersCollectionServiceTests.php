<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountOrdersCollectionService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{PurchasesFixture,
    UsersFixture};
use app\collections\PurchasesCollection;
use app\helpers\HashHelper;
use app\models\UsersModel;

/**
 * Тестирует класс AccountOrdersCollectionService
 */
class AccountOrdersCollectionServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
                'users'=>UsersFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AccountOrdersCollectionService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountOrdersCollectionService::class);
        
        $this->assertTrue($reflection->hasProperty('purchasesCollection'));
    }
    
    /**
     * Тестирует метод AccountOrdersCollectionService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AccountOrdersCollectionService();
        $service->handle();
    }
    
    /**
     * Тестирует метод AccountOrdersCollectionService::handle
     * page === null
     */
    public function testHandleClean()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AccountOrdersCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод AccountOrdersCollectionService::handle
     * page === true
     */
    public function testHandlePage()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 2;
            }
        };
        
        $service = new AccountOrdersCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }

    /**
     * Тестирует метод AccountOrdersCollectionService::handle
     * page === null
     * filters === true
     */
    public function testHandleFilters()
    {
        $key = HashHelper::createHash([\Yii::$app->params['ordersFilters']]);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'sortingType'=>SORT_ASC
        ]);

        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new AccountOrdersCollectionService();
        $result = $service->handle($request);

        $this->assertInstanceOf(PurchasesCollection::class, $result);

        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
