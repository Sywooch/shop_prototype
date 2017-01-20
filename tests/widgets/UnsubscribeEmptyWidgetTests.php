<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UnsubscribeEmptyWidget;

/**
 * Тестирует класс UnsubscribeEmptyWidget
 */
class UnsubscribeEmptyWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UnsubscribeEmptyWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UnsubscribeEmptyWidget::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::run
     * если пуст UnsubscribeEmptyWidget::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testRunEmptyEmail()
    {
        $widget = new UnsubscribeEmptyWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::run
     * если пуст UnsubscribeEmptyWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new UnsubscribeEmptyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some@some.com');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::run
     */
    public function testRun()
    {
        $widget = new UnsubscribeEmptyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some@some.com');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'unsubscribe-empty.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Отписаться</strong></p>#', $result);
        $this->assertRegExp('#<p>Email some@some.com не связан ни с одной рассылкой!</p>#', $result);
    }
}
