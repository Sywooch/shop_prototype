<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountContactsWidget;
use app\models\{CurrencyModel,
    UsersModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;

/**
 * Тестирует класс AccountContactsWidget
 */
class AccountContactsWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AccountContactsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountContactsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('user'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AccountContactsWidget::setUser
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetUserError()
    {
        $user = new class() {};
        
        $widget = new AccountContactsWidget();
        $widget->setUser($user);
    }
    
    /**
     * Тестирует метод AccountContactsWidget::setUser
     */
    public function testSetUser()
    {
        $user = new class() extends UsersModel {};
        
        $widget = new AccountContactsWidget();
        $widget->setUser($user);
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(UsersModel::class, $result);
    }
    
    /**
     * Тестирует метод AccountContactsWidget::run
     * если пуст AccountContactsWidget::user
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: user
     */
    public function testRunEmptyUser()
    {
        $widget = new AccountContactsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountContactsWidget::run
     * если пуст AccountContactsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new AccountContactsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountContactsWidget::run
     */
    public function testRun()
    {
        $user = UsersModel::findOne(1);
        
        $widget = new AccountContactsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $user);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-contacts.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Текущие контактные данные</strong></p>#', $result);
        $this->assertRegExp('#<div class="account-user-info">#', $result);
        $this->assertRegExp('#Email: light@mail.some<br>#', $result);
        $this->assertRegExp('#Имя: John<br>#', $result);
        $this->assertRegExp('#Фамилия: Doe<br>#', $result);
        $this->assertRegExp('#Телефон: \+290865687812<br>#', $result);
        $this->assertRegExp('#Адрес: Main Street Kangaroo Point<br>#', $result);
        $this->assertRegExp('#Город: New York<br>#', $result);
        $this->assertRegExp('#Страна: USA<br>#', $result);
        $this->assertRegExp('#Почтовый код: 09100<br>#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
