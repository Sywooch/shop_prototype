<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\EmptyProductsWidget;

/**
 * Тестирует класс EmptyProductsWidget
 */
class EmptyProductsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства EmptyProductsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmptyProductsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод EmptyProductsWidget::run
     * если EmptyProductsWidget::view пусто
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $widget = new EmptyProductsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmptyProductsWidget::run
     */
    public function testRun()
    {
        $widget = new EmptyProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'empty-products.twig');
        
        $result = $widget->run();
        
        $expected = sprintf('<p>%s</p>', \Yii::t('base', 'Search by this parameters returned no results'));
        
        $this->assertSame($expected, trim($result));
    }
}
