<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CartHandlerTrait;
use app\models\CurrencyModel;
use app\collections\{PurchasesCollection,
    PurchasesCollectionInterface};
use app\forms\PurchaseForm;

/**
 * Тестирует класс CartHandlerTrait
 */
class CartHandlerTraitTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new class() {
            use CartHandlerTrait;
        };
    }
    
    /**
     * Тестирует метод CartHandlerTrait::shortCartWidgetAjaxConfig
     * если запрос с ошибками
     */
    public function testShortCartWidgetAjaxConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartWidgetAjaxConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод CartHandlerTrait::cartWidgetConfig
     * если запрос с ошибками
     */
    public function testCartWidgetConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'cartWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('updateForm', $result);
        $this->assertArrayHasKey('deleteForm', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(PurchaseForm::class, $result['updateForm']);
        $this->assertInstanceOf(PurchaseForm::class, $result['deleteForm']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод CartHandlerTrait::shortCartRedirectWidgetConfig
     * если запрос с ошибками
     */
    public function testShortCartRedirectWidgetConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartRedirectWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
}
