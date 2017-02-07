<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCsvProductsFormWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс AdminCsvProductsFormWidget
 */
class AdminCsvProductsFormWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminCsvProductsFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCsvProductsFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
        $this->assertTrue($reflection->hasProperty('isAllowed'));
    }
    
    /**
     * Тестирует метод AdminCsvProductsFormWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AdminCsvProductsFormWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AdminCsvProductsFormWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AdminCsvProductsFormWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminCsvProductsFormWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminCsvProductsFormWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminCsvProductsFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminCsvProductsFormWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminCsvProductsFormWidget::run
     * если пуст AdminCsvProductsFormWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new AdminCsvProductsFormWidget();
        $widget->run();
    }
    
    
    /**
     * Тестирует метод AdminCsvProductsFormWidget::run
     * если пуст AdminCsvProductsFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new AdminCsvProductsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminCsvProductsFormWidget::run
     */
    public function testRun()
    {
        $widget = new AdminCsvProductsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-csv-products-form.twig');
        
        $reflection = new \ReflectionProperty($widget, 'isAllowed');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, true);
        
        $result = $widget->run();

        $this->assertRegExp('#<div class="get-csv">#', $result);
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p class="csv-success"></p>#', $result);
        $this->assertRegExp('#<form id="admin-scv-products-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Получить ссылку">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
