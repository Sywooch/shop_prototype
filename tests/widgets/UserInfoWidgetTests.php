<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UserInfoWidget;
use yii\web\User;
use app\forms\AbstractBaseForm;

class UserInfoWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new UserInfoWidget();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserInfoWidget::class);
        
        $this->assertTrue($reflection->hasProperty('user'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UserInfoWidget::setUser
     */
    public function testSetUser()
    {
        $user = new class() extends User {
            public $identityClass = 'SomeClass';
        };
        
        $this->widget->setUser($user);
        
        $reflection = new \ReflectionProperty($this->widget, 'user');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(User::class, $result);
    }
    
    /**
     * Тестирует метод UserInfoWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($form);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод UserInfoWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $this->widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
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
        $this->widget->run();
    }
    
    /**
     * Тестирует метод PaginationWidget::run
     * если пуст PaginationWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод PaginationWidget::run
     * если пуст PaginationWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
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
        
        $form = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $user);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'user-info.twig');
        
        $result = $this->widget->run();
        
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
        
        $form = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $user);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'user-info.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div class="user-info">#', $result);
        $this->assertRegExp('#<p>Привет, some@some.com!</p>#', $result);
        $this->assertRegExp('#<form id="user-logout-form"#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="1">#', $result);
        $this->assertRegExp('#<input type="submit" value="Выйти">#', $result);
        $this->assertRegExp('#<a href=".+">Настройки аккаунта</a>#', $result);
    }
}
