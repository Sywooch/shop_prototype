<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UnsubscribeSuccessWidget;

/**
 * Тестирует класс UnsubscribeSuccessWidget
 */
class UnsubscribeSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UnsubscribeSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UnsubscribeSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод UnsubscribeSuccessWidget::setMailings
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mailing = new class() {};
        
        $widget = new UnsubscribeSuccessWidget();
        $widget->setMailings($mailing);
    }
    
    /**
     * Тестирует метод UnsubscribeSuccessWidget::setMailings
     */
    public function testSetMailings()
    {
        $mailing = new class() {};
        
        $widget = new UnsubscribeSuccessWidget();
        $widget->setMailings([$mailing]);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UnsubscribeSuccessWidget::run
     * если пуст UnsubscribeSuccessWidget::mailings
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailings
     */
    public function testRunEmptyMailings()
    {
        $widget = new UnsubscribeSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeSuccessWidget::run
     * если пуст UnsubscribeSuccessWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mailing = new class() {};
        
        $widget = new UnsubscribeSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mailing]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeSuccessWidget::run
     */
    public function testRun()
    {
        $mailings = [
            new class() {
                public $name = 'One';
                public $description = 'One description';
            },
            new class() {
                public $name = 'Two';
                public $description = 'Two description';
            },
        ];
        
        $widget = new UnsubscribeSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'mailings-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Вы успешно отписаны от рассылок:</strong></p>#', $result);
        $this->assertRegExp('#<ol>#', $result);
        $this->assertRegExp('#<li>#', $result);
        $this->assertRegExp('#<strong>One</strong>#', $result);
        $this->assertRegExp('#<br/>One description#', $result);
        $this->assertRegExp('#<strong>Two</strong>#', $result);
        $this->assertRegExp('#<br/>Two description#', $result);
    }
}
