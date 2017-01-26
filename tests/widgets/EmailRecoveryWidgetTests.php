<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\EmailRecoveryWidget;
use app\controllers\UserController;

/**
 * Тестирует класс EmailRecoveryWidget
 */
class EmailRecoveryWidgetTests extends TestCase
{
    /**
     * Тестирует свойства EmailRecoveryWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailRecoveryWidget::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new EmailRecoveryWidget();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new EmailRecoveryWidget();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::setEmail
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $email = null;
        
        $widget = new EmailRecoveryWidget();
        $widget->setEmail($email);
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::setEmail
     */
    public function testSetEmail()
    {
        $email = 'email';
        
        $widget = new EmailRecoveryWidget();
        $widget->setEmail($email);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new EmailRecoveryWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new EmailRecoveryWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::run
     * если пуст EmailRecoveryWidget::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testRunKeyEmpty()
    {
        $widget = new EmailRecoveryWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::run
     * если пуст EmailRecoveryWidget::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testRunEmailEmpty()
    {
        $key = sha1('some key');
        
        $widget = new EmailRecoveryWidget();
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $key);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::run
     * если пуст EmailRecoveryWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $key = sha1('some key');
        
        $widget = new EmailRecoveryWidget();
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $key);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some@some.com');
        
        $widget->run();
    }
    
     /**
     * Тестирует метод EmailRecoveryWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $key = sha1('some key');
        
        $widget = new EmailRecoveryWidget();
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $key);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some@some.com');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'recovery-mail.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<h1>Привет! Это руководство для восстановления вашего пароля!</h1>#', $result);
        $this->assertRegExp('#<p>Для того, чтобы мы сгенерировали для вас новый пароль, просто перейдите по это ссылке#', $result);
        $this->assertRegExp('#<a href=".+\?recovery=ab0d8e0ce58e6fa9d1b230d25f2ea0b44a51ebd4&amp;email=some%40some.com">.+\?recovery=ab0d8e0ce58e6fa9d1b230d25f2ea0b44a51ebd4&amp;email=some%40some.com</a>#', $result);
    }
}
