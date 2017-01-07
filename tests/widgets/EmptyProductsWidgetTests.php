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
     * если пуст EmptyProductsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
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
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'empty-products.twig');
        
        $result = $widget->run();
        
        $this->assertEquals('<p>Поиск по этим параметрам не дал результатов</p>', trim($result));
    }
}
