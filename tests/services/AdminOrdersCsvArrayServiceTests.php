<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AdminOrdersCsvArrayService;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\helpers\HashHelper;

/**
 * Тестирует класс AdminOrdersCsvArrayService
 */
class AdminOrdersCsvArrayServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminOrdersCsvArrayService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersCsvArrayService::class);
        
        $this->assertTrue($reflection->hasProperty('purchasesArray'));
    }
    
    /**
     * Тестирует метод AdminOrdersCsvArrayService::handle
     * filters === false
     */
    public function testHandleFiltersFalse()
    {
        $service = new AdminOrdersCsvArrayService();
        $result = $service->handle();

        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminOrdersCsvArrayService::handle
     * filters === true
     */
    public function testHandleFilters()
    {
        $key = HashHelper::createHash([\Yii::$app->params['ordersFilters']]);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'status'=>'shipped'
        ]);

        $service = new AdminOrdersCsvArrayService();
        $result = $service->handle();

        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);

        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
