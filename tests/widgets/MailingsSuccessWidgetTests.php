<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\MailingsSuccessWidget;

/**
 * Тестирует класс MailingsSuccessWidget
 */
class MailingsSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства MailingsSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод MailingsSuccessWidget::setMailings
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mailing = new class() {};
        
        $widget = new MailingsSuccessWidget();
        $widget->setMailings($mailing);
    }
    
    /**
     * Тестирует метод MailingsSuccessWidget::setMailings
     */
    public function testSetMailings()
    {
        $mailing = new class() {};
        
        $widget = new MailingsSuccessWidget();
        $widget->setMailings([$mailing]);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsSuccessWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new MailingsSuccessWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод MailingsSuccessWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new MailingsSuccessWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод MailingsSuccessWidget::run
     * если пуст MailingsSuccessWidget::mailings
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailings
     */
    public function testRunEmptyMailings()
    {
        $widget = new MailingsSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsSuccessWidget::run
     * если пуст MailingsSuccessWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mailing = new class() {};
        
        $widget = new MailingsSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mailing]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsSuccessWidget::run
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
        
        $widget = new MailingsSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'mailings-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Вы успешно подписались на рассылки:</strong></p>#', $result);
        $this->assertRegExp('#<ol>#', $result);
        $this->assertRegExp('#<li>#', $result);
        $this->assertRegExp('#<strong>One</strong>#', $result);
        $this->assertRegExp('#<br/>One description#', $result);
        $this->assertRegExp('#<strong>Two</strong>#', $result);
        $this->assertRegExp('#<br/>Two description#', $result);
    }
}
