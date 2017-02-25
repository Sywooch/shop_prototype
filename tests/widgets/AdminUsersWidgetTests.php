<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminUsersWidget;
use app\models\UsersModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;

/**
 * Тестирует класс AdminUsersWidget
 */
class AdminUsersWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminUsersWidget();
    }
    
    /**
     * Тестирует свойства AdminUsersWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUsersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('users'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminUsersWidget::setUsers
     */
    public function testSetUsers()
    {
        $users = [new class() {}];
        
        $this->widget->setUsers($users);
        
        $reflection = new \ReflectionProperty($this->widget, 'users');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminUsersWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $this->widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminUsersWidget::setTemplate
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
     * Тестирует метод AdminUsersWidget::run
     * если пуст AdminUsersWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminUsersWidget::run
     * если пуст AdminUsersWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminUsersWidget::run
     * если нет заказов
     */
    public function testRunEmptyUsers()
    {
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-users.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Пользователей нет</p>#', $result);
    }
    
    /**
     * Тестирует метод AdminUsersWidget::run
     */
    public function testRun()
    {
        $users = UsersModel::findAll([1, 2, 3]);
        
        $reflection = new \ReflectionProperty($this->widget, 'users');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $users);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-users.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#Email: [a-z]+@[a-z]+.[a-z]+#', $result);
        $this->assertRegExp('#Имя: [a-zA-Z]+#', $result);
        $this->assertRegExp('#Фамилия: [a-zA-Z]+#', $result);
        $this->assertRegExp('#Телефон: [0-9-()]+#', $result);
        $this->assertRegExp('#Адрес: .+#', $result);
        $this->assertRegExp('#Город: [a-zA-Z]+#', $result);
        $this->assertRegExp('#Страна: [a-zA-Z]+#', $result);
        $this->assertRegExp('#Почтовый код: [a-zA-Z0-9]+#', $result);
        $this->assertRegExp('#Заказы: [0-9]{1,3}#', $result);
        $this->assertRegExp('#<a href=".+/admin-user-.+">.+</a>#', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
