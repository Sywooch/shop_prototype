<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\MailingsUnsubscribeEmptyWidget;

/**
 * Тестирует класс MailingsUnsubscribeEmptyWidget
 */
class MailingsUnsubscribeEmptyWidgetTests extends TestCase
{
    /**
     * Тестирует свойства MailingsUnsubscribeEmptyWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsUnsubscribeEmptyWidget::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeEmptyWidget::run
     * если пуст MailingsUnsubscribeEmptyWidget::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testRunEmptyEmail()
    {
        $widget = new MailingsUnsubscribeEmptyWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeEmptyWidget::run
     * если пуст MailingsUnsubscribeEmptyWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new MailingsUnsubscribeEmptyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some@some.com');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsUnsubscribeEmptyWidget::run
     */
    public function testRun()
    {
        $widget = new MailingsUnsubscribeEmptyWidget();
        
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
