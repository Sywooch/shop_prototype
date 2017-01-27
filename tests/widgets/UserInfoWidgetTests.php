<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UserInfoWidget;
use yii\web\User;

class UserInfoWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserInfoWidget::class);
        
        $this->assertTrue($reflection->hasProperty('user'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UserInfoWidget::setUser
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetUserError()
    {
        $user = new class() {};
        
        $widget = new UserInfoWidget();
        $widget->setUser($user);
    }
    
    /**
     * Тестирует метод UserInfoWidget::setUser
     */
    public function testSetUser()
    {
        $user = new class() extends User {
            public $identityClass = 'SomeClass';
        };
        
        $widget = new UserInfoWidget();
        $widget->setUser($user);
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(User::class, $result);
    }
    
    /**
     * Тестирует метод UserInfoWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new UserInfoWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод UserInfoWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new UserInfoWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::run
     * если пуст PaginationWidget::user
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: user
     */
    public function testRunEmptyUser()
    {
        $widget = new UserInfoWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PaginationWidget::run
     * если пуст PaginationWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $user = new class() extends User {
            public $identityClass = 'SomeClass';
        };
        
        $widget = new UserInfoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $user);
        
        $widget->run();
    }
    
     /**
     * Тестирует метод PaginationWidget::run
     * если Guest
     */
    public function testRunGuest()
    {
        \Yii::$app->user->logout();
        
        $user = new class() extends User {
            public $identityClass = 'SomeClass';
        };
        
        $widget = new UserInfoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $user);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'user-info.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<div class="user-info">/', $result);
        $this->assertRegExp('/<p>' . \Yii::t('base', 'Hello, {placeholder}!', ['placeholder'=>\Yii::t('base', 'Guest')]) . '<\/p>/', $result);
    }
    
    /**
     * Тестирует метод PaginationWidget::run
     */
    public function testRunUser()
    {
        $user = new class() extends User {
            public $identityClass = 'SomeClass';
            public $isGuest = false;
            public $identity;
            public function __construct()
            {
                $this->identity = new class() {
                    public $id = 1;
                    public $email;
                    public function __construct()
                    {
                        $this->email = new class() {
                            public $email = 'some@some.com';
                        };
                    }
                };
            }
        };
        
        $widget = new UserInfoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $user);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'user-info.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="user-info">#', $result);
        $this->assertRegExp('#<p>Привет, some@some.com!</p>#', $result);
        $this->assertRegExp('#<form id="user-logout-form"#', $result);
        $this->assertRegExp('#<input type="hidden" id="userloginform-id"#', $result);
        $this->assertRegExp('#<input type="submit" value="Выйти">#', $result);
        $this->assertRegExp('#<form id="account-settings-form" action=".+" method="GET">#', $result);
        $this->assertRegExp('#<input type="submit" value="Настройки аккаунта">#', $result);
    }
}
