<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CategoriesOptionWidget;

/**
 * Тестирует класс CategoriesOptionWidget
 */
class CategoriesOptionWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new CategoriesOptionWidget();
    }
    
    /**
     * Тестирует свойства CategoriesOptionWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesOptionWidget::class);
        
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод CategoriesOptionWidget::setCategories
     */
    public function testSetCategories()
    {
        $categories = [new class() {}];
        
        $this->widget->setCategories($categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод CategoriesOptionWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $this->widget->setTemplate('Template');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CategoriesOptionWidget::run
     * если пуст CategoriesOptionWidget::categories
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: categories
     */
    public function testRunEmptyCategories()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод CategoriesOptionWidget::run
     * если пуст CategoriesOptionWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = [new class() {}];
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод CategoriesOptionWidget::run
     */
    public function testRun()
    {
        $categories = [
            new class() {
                public $id = 1;
                public $name = 'First';
            },
            new class() {
                public $id = 2;
                public $name = 'Second';
            },
            new class() {
                public $id = 3;
                public $name = 'Three';
            }
        ];
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'options.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<option value="0">------------------------</option>#', $result);
        $this->assertRegExp('#<option value="1">First</option>#', $result);
        $this->assertRegExp('#<option value="2">Second</option>#', $result);
        $this->assertRegExp('#<option value="3">Three</option>#', $result);
    }
}
