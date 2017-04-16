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
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::setTempPassword
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTempPasswordError()
    {
        $tempPassword = null;
        
        $widget = new PasswordGenerateSuccessWidget();
        $widget->setTempPassword($tempPassword);
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::setTempPassword
     */
    public function testSetTempPassword()
    {
        $tempPassword = 'tempPassword';
        
        $widget = new PasswordGenerateSuccessWidget();
        $widget->setTempPassword($tempPassword);
        
        $reflection = new \ReflectionProperty($widget, 'tempPassword');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new PasswordGenerateSuccessWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new PasswordGenerateSuccessWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new PasswordGenerateSuccessWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new PasswordGenerateSuccessWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
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
     * если пуст PasswordGenerateSuccessWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new PasswordGenerateSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'tempPassword');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'tempPassword');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::run
     * если пуст PasswordGenerateSuccessWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new PasswordGenerateSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'tempPassword');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'tempPassword');
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод PasswordGenerateSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new PasswordGenerateSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'tempPassword');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'tempPassword');
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'generate-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Ваш новый пароль: <strong>tempPassword</strong></p>#', $result);
        $this->assertRegExp('#<p>В целях безопасности, рекомендуем сменить его как можно скорее!</p>#', $result);
    }
}
