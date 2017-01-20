<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountMailingsWidget;

/**
 * Тестирует класс AccountMailingsWidget
 */
class AccountMailingsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AccountMailingsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountMailingsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AccountMailingsWidget::setMailings
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mailings = new class() {};
        
        $widget = new AccountMailingsWidget();
        $widget->setMailings($mailings);
    }
    
    /**
     * Тестирует метод AccountMailingsWidget::setMailings
     */
    public function testSetMailings()
    {
        $mailings = [new class() {}];
        
        $widget = new AccountMailingsWidget();
        $widget->setMailings($mailings);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AccountMailingsWidget::run
     * если пуст AccountMailingsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new AccountMailingsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsWidget::run
     * если подписки на рассылки отсутствуют
     */
    public function testRunWithoutMailings()
    {
        $mailings = [];
        
        $widget = new AccountMailingsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-mailings.twig');
        
        $result = $widget->run();
        
        $this->assertEmpty($result);
    }
    
    /**
     * Тестирует метод AccountMailingsWidget::run
     * если есть подписки на рассылки
     */
    public function testRun()
    {
        $mailings = [
            new class() {
                public $name = 'Mailing 1';
                public $description = 'Mailing description 1';
            },
            new class() {
                public $name = 'Mailing 2';
                public $description = 'Mailing description 2';
            },
        ];
        
        $widget = new AccountMailingsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-mailings.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Текущие подписки</strong></p>#', $result);
        $this->assertRegExp('#Mailing 1#', $result);
        $this->assertRegExp('#<br>Mailing description 1#', $result);
        $this->assertRegExp('#Mailing 2#', $result);
        $this->assertRegExp('#<br>Mailing description 2#', $result);
    }
}
