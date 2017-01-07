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
        $this->assertTrue($reflection->hasProperty('view'));
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
        $reflection->setValue($widget, $key);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailRecoveryWidget::run
     * если пуст EmailRecoveryWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunViewEmpty()
    {
        $key = sha1('some key');
        
        $widget = new EmailRecoveryWidget();
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setValue($widget, $key);
        
        $reflection = new \ReflectionProperty($widget, 'email');
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
        $reflection->setValue($widget, $key);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setValue($widget, 'some@some.com');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'recovery-mail.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<h1>Привет! Это руководство для восстановления вашего пароля!</h1>#', $result);
        $this->assertRegExp('#<p>Для того, чтобы мы сгенерировали для вас новый пароль, просто перейдите по это ссылке#', $result);
        $this->assertRegExp('#<a href=".+\?recovery=ab0d8e0ce58e6fa9d1b230d25f2ea0b44a51ebd4&amp;email=some%40some.com">.+\?recovery=ab0d8e0ce58e6fa9d1b230d25f2ea0b44a51ebd4&amp;email=some%40some.com</a>#', $result);
    }
}
