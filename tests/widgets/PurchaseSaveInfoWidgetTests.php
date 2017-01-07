<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PurchaseSaveInfoWidget;

/**
 * Тестирует класс PurchaseSaveInfoWidget
 */
class PurchaseSaveInfoWidgetTests extends TestCase
{
    /**
     * Тестирует свойства PurchaseSaveInfoWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchaseSaveInfoWidget::class);
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод PurchaseSaveInfoWidget::run
     * если пуст PurchaseSaveInfoWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new PurchaseSaveInfoWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PurchaseSaveInfoWidget::run
     */
    public function testRun()
    {
        $widget = new PurchaseSaveInfoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'save-purchase-info.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Товар успешно добавлен в корзину!</p>#', $result);
    }
}
