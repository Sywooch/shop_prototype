<?php

namespace app\tests\widgtes;

use PHPUnit\Framework\TestCase;
use app\widgets\PasswordGenerateSuccessWidget;

/**
 * Тестирует класс PasswordGenerateSuccessWidget
 */
class PasswordGenerateSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства PasswordGenerateSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PasswordGenerateSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('tempPassword'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::run
     * если пуст PasswordGenerateSuccessWidget::tempPassword
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: tempPassword
     */
    public function testRunEmptyTempPassword()
    {
        $widget = new PasswordGenerateSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::run
     * если пуст PasswordGenerateSuccessWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new PasswordGenerateSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'tempPassword');
        $reflection->setValue($widget, 'tempPassword');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new PasswordGenerateSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'tempPassword');
        $reflection->setValue($widget, 'tempPassword');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'generate-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Восстановление пароля</strong></p>#', $result);
        $this->assertRegExp('#<p>Ваш новый пароль: <strong>tempPassword</strong></p>#', $result);
        $this->assertRegExp('#<p>В целях безопасности, рекомендуем сменить его как можно скорее!</p>#', $result);
    }
}
