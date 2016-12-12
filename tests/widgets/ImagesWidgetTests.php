<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ImagesWidget;

/**
 * Тестирует класс ImagesWidget
 */
class ImagesWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ImagesWidget::class);
        
        $this->assertTrue($reflection->hasProperty('path'));
        $this->assertTrue($reflection->hasProperty('view'));
        $this->assertTrue($reflection->hasProperty('result'));
    }
    
    /**
     * Тестирует метод ImagesWidget::run
     * при отсутствии ImagesWidget::path
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: path
     */
    public function testRunEmptyPath()
    {
        $widget = new ImagesWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ImagesWidget::run
     * при отсутствии ImagesWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $widget = new ImagesWidget();
        
        $reflection = new \ReflectionProperty($widget, 'path');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'test');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ImagesWidget::run
     */
    public function testRun()
    {
        $widget = new ImagesWidget();
        
        $reflection = new \ReflectionProperty($widget, 'path');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'test');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'images.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<div class="images">/', $result);
        $this->assertRegExp('/<img src=".+" alt=""><br\/>/', $result);
    }
}
