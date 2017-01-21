<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountChangeDataSuccessWidget;

/**
 * Тестирует класс AccountChangeDataSuccessWidget
 */
class AccountChangeDataSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AccountChangeDataSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangeDataSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AccountChangeDataSuccessWidget::run
     * если пуст AccountChangeDataSuccessWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new AccountChangeDataSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountChangeDataSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new AccountChangeDataSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-change-data-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Данные успешно обновлены!</p>#', $result);
    }
}
