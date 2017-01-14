<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\SuccessSendPurchaseWidget;

/**
 * Тестирует класс SuccessSendPurchaseWidget
 */
class SuccessSendPurchaseWidgetTests extends TestCase
{
    /**
     * Тестирует свойства SuccessSendPurchaseWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SuccessSendPurchaseWidget::class);
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод SuccessSendPurchaseWidget::run
     * если пуст SuccessSendPurchaseWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new SuccessSendPurchaseWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод SuccessSendPurchaseWidget::run
     */
    public function testRun()
    {
        $widget = new SuccessSendPurchaseWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'success-send-purchase.twig');
        
        $result = $widget->run();
        
        $this->assertEquals('<p>Ваш заказ был успешно отправлен</p>', trim($result));
    }
}
