<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UnsubscribeFormWidget;
use app\forms\MailingForm;
use app\controllers\MailingsController;

/**
 * Тестирует класс UnsubscribeFormWidget
 */
class UnsubscribeFormWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UnsubscribeFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UnsubscribeFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new UnsubscribeFormWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends MailingForm {};
        
        $widget = new UnsubscribeFormWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(MailingForm::class, $result);
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::setMailings
     * если передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mock = new class() {};
        
        $widget = new UnsubscribeFormWidget();
        $widget->setMailings($mock);
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::setMailings
     */
    public function testSetMailings()
    {
        $mock = [new class() {}];
        
        $widget = new UnsubscribeFormWidget();
        $widget->setMailings($mock);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new UnsubscribeFormWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new UnsubscribeFormWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::run
     * если пуст UnsubscribeFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new UnsubscribeFormWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::run
     * если пуст UnsubscribeFormWidget::mailings
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailings
     */
    public function testRunEmptyMailings()
    {
        $mock = new class() {};
        
        $widget = new UnsubscribeFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::run
     * если пуст UnsubscribeFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new UnsubscribeFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new MailingsController('mailing', \Yii::$app);
        
        $form = new class() extends MailingForm {
            public $email = 'some@some.com';
            public $key = 'someKey';
        };
        
        $mailings = [
            new class() {
                public $id = 1;
                public $name = 'One';
                public $description = 'One description';
            },
            new class() {
                public $id = 2;
                public $name = 'Two';
                public $description = 'Two description';
            },
        ];
        
        $widget = new UnsubscribeFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'unsubscribe-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Отписаться</strong></p>#', $result);
        $this->assertRegExp('#<p>Выберите подписки, которые вы хотите отменить</p>#', $result);
        $this->assertRegExp('#<form id="unsubscribe-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[id\]\[\]" value="1"> One</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[id\]\[\]" value="2"> Two</label>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[email\]" value="some@some.com">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[key\]" value="someKey">#', $result);
        $this->assertRegExp('#<input type="submit" value="Отписаться">#', $result);
    }
}
