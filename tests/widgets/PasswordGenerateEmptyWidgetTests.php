<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PasswordGenerateEmptyWidget;

/**
 * Тестирует класс PasswordGenerateEmptyWidget
 */
class PasswordGenerateEmptyWidgetTests extends TestCase
{
    /**
     * Тестирует свойства PasswordGenerateEmptyWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PasswordGenerateEmptyWidget::class);
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод PasswordGenerateEmptyWidget::run
     * если пуст PasswordGenerateEmptyWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $widget = new PasswordGenerateEmptyWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PasswordGenerateEmptyWidget::run
     */
    public function testRun()
    {
        $widget = new PasswordGenerateEmptyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'generate-empty.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Восстановление пароля</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>К сожалению, ссылка по которой вы перешли недействительна. Для решения этой проблемы вы можете обратиться к администратору</strong></p>#', $result);
    }
}
