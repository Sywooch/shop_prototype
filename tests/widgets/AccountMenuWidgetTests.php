<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountMenuWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\{PurchasesFixture,
    UsersFixture};
use app\models\UsersModel;

/**
 * Тестирует класс AccountMenuWidget
 */
class AccountMenuWidgetTests extends TestCase
{
    private $dbClass;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'purchases'=>PurchasesFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('options'));
    }
    
    /**
     * Тестирует метод AccountMenuWidget::setItems
     * если нет заказов, связанных с пользователем
     */
    public function testSetItemsNotOrders()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{purchases}}')->execute();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $widget = new AccountMenuWidget();
        
        $reflection = new \ReflectionMethod($widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(5, $result);
    }
    
    /**
     * Тестирует метод AccountMenuWidget::setItems
     */
    public function testSetItems()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $widget = new AccountMenuWidget();
        
        $reflection = new \ReflectionMethod($widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(6, $result);
    }
    
    /**
     * Тестирует метод AccountMenuWidget::run
     */
    public function testRun()
    {
        $widget = new AccountMenuWidget();
        $widget->run();
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
