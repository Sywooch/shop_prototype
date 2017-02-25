<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminUserMenuWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;

/**
 * Тестирует класс AdminUserMenuWidget
 */
class AdminUserMenuWidgetTests extends TestCase
{
    private $widget;
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'orders'=>PurchasesFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminUserMenuWidget();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('options'));
    }
    
    /**
     * Тестирует метод AdminUserMenuWidget::setItems
     */
    public function testSetItemsNotOrders()
    {
        $usersModel = new class() {
            public $id = 1;
        };
        
        $reflection = new \ReflectionMethod($this->widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($this->widget);
        
        $reflection = new \ReflectionProperty($this->widget, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(9, $result);
    }
    
    /**
     * Тестирует метод AdminUserMenuWidget::run
     */
    public function testRun()
    {
        $this->widget->run();
        
        $this->assertTrue(true);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
