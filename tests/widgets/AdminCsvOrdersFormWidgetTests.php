<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCsvOrdersFormWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;

/**
 * Тестирует класс AdminCsvOrdersFormWidget
 */
class AdminCsvOrdersFormWidgetTests extends TestCase
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
     * Тестирует свойства AdminCsvOrdersFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCsvOrdersFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
        $this->assertTrue($reflection->hasProperty('isAllowed'));
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AdminCsvOrdersFormWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AdminCsvOrdersFormWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminCsvOrdersFormWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminCsvOrdersFormWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::run
     * если пуст AdminCsvOrdersFormWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new AdminCsvOrdersFormWidget();
        $widget->run();
    }
    
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::run
     * если пуст AdminCsvOrdersFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new AdminCsvOrdersFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::run
     */
    public function testRun()
    {
        $widget = new AdminCsvOrdersFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-csv-orders-form.twig');
        
        $reflection = new \ReflectionProperty($widget, 'isAllowed');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, true);
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="get-csv">#', $result);
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p class="csv-success"></p>#', $result);
        $this->assertRegExp('#<form id="admin-scv-orders-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Получить ссылку">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
