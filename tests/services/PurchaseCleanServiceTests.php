<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\PurchaseCleanService;
use app\helpers\HashHelper;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};

/**
 * Тестирует класс PurchaseCleanService
 */
class PurchaseCleanServiceTests extends TestCase
{
     private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод PurchaseCleanService::handle
     * если не переад $request
     * @expectedException ErrorException
     */
    public function testHandleRequestError()
    {
        $service = new PurchaseCleanService();
        $service->handle();
    }
    
    /**
     * Тестирует метод PurchaseCleanService::handle
     */
    public function testHandle()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(HashHelper::createCartKey(), [['id_product'=>1, 'quantity'=>1, 'id_color'=>1, 'id_size'=>1, 'price'=>123.87]]);
        
        $this->assertTrue($session->has(HashHelper::createCartKey()));
        
        $request = new class() {
            public $isAjax = true;
        };
        
        $service = new PurchaseCleanService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertRegExp('#<p>Товаров в корзине: 0, Общая стоимость: 0,00 UAH</p>#', $result);
        
        $session->remove(HashHelper::createCartKey());
        $session->open();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
