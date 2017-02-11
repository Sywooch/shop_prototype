<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\BaseHandlerTrait;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyInterface;
use app\exceptions\ExceptionsTrait;
use app\collections\PurchasesCollectionInterface;

/**
 * Тестирует класс BaseHandlerTrait
 */
class BaseHandlerTraitTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new class() {
            use BaseHandlerTrait, ExceptionsTrait;
        };
    }
    
    /**
     * Тестирует метод BaseHandlerTrait::getCurrentCurrency
     */
    public function testGetCurrentCurrency()
    {
        $reflection = new \ReflectionMethod($this->handler, 'getCurrentCurrency');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInstanceOf(CurrencyInterface::class, $result);
    }
    
    /**
     * Тестирует метод BaseHandlerTrait::getOrdersSessionCollection
     */
    public function testGetOrdersSessionCollection()
    {
        $reflection = new \ReflectionMethod($this->handler, 'getOrdersSessionCollection');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
