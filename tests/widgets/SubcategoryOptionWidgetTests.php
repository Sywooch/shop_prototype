<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\SubcategoryOptionWidget;

/**
 * Тестирует класс SubcategoryOptionWidget
 */
class SubcategoryOptionWidgetTests extends TestCase
{
    /**
     * Тестирует свойства SubcategoryOptionWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubcategoryOptionWidget::class);
        
        $this->assertTrue($reflection->hasProperty('subcategoryArray'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод SubcategoryOptionWidget::setSubcategoryArray
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSubcategoryArrayError()
    {
        $subcategory = null;
        
        $widget = new SubcategoryOptionWidget();
        $widget->setSubcategoryArray($subcategory);
    }
    
    /**
     * Тестирует метод SubcategoryOptionWidget::setSubcategoryArray
     */
    public function testSetSubcategoryArray()
    {
        $subcategory = [null];
        
        $widget = new SubcategoryOptionWidget();
        $widget->setSubcategoryArray($subcategory);
        
        $reflection = new \ReflectionProperty($widget, 'subcategoryArray');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод SubcategoryOptionWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new SubcategoryOptionWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод SubcategoryOptionWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new SubcategoryOptionWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод SubcategoryOptionWidget::run
     * если пуст SubcategoryOptionWidget::subcategory
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: subcategory
     */
    public function testRunEmptySubcategory()
    {
        $widget = new SubcategoryOptionWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод SubcategoryOptionWidget::run
     * если пуст SubcategoryOptionWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = 'mock';
        
        $widget = new SubcategoryOptionWidget();
       
        $reflection = new \ReflectionProperty($widget, 'subcategoryArray');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод SubcategoryOptionWidget::run
     */
    public function testRun()
    {
        $subcategoryArray = [1=>'First', 2=>'Second', 3=>'Three'];
        
        $widget = new SubcategoryOptionWidget();
        
        $reflection = new \ReflectionProperty($widget, 'subcategoryArray');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $subcategoryArray);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'subcategory-option.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<option value="1">First</option>#', $result);
        $this->assertRegExp('#<option value="2">Second</option>#', $result);
        $this->assertRegExp('#<option value="3">Three</option>#', $result);
    }
}