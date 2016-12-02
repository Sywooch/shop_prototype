<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ThumbnailsWidget;

/**
 * Тестирует класс ThumbnailsWidget
 */
class ThumbnailsWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ThumbnailsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('path'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод ThumbnailsWidget::run
     * при отсутствии ThumbnailsWidget::path
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: path
     */
    public function testRunEmptyPath()
    {
        $widget = new ThumbnailsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ThumbnailsWidget::run
     * при отсутствии ThumbnailsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $widget = new ThumbnailsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'path');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'test');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ThumbnailsWidget::run
     */
    public function testRun()
    {
        $widget = new ThumbnailsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'path');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'test');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'thumbnails.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<div class="thumbnails">/', $result);
        $this->assertRegExp('/<img src=".+" alt="">/', $result);
    }
}
