<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UnsubscribeFormWidget;
use app\forms\AbstractBaseForm;
use app\controllers\MailingsController;

/**
 * Тестирует класс UnsubscribeFormWidget
 */
class UnsubscribeFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new UnsubscribeFormWidget();
    }
    
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
     */
    public function testSetForm()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($form);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::setMailings
     */
    public function testSetMailings()
    {
        $mock = [new class() {}];
        
        $this->widget->setMailings($mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $this->widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
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
        $result = $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $result = $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeFormWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new MailingsController('mailing', \Yii::$app);
        
        $form = new class() extends AbstractBaseForm {
            public $id;
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
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mailings);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'unsubscribe-form.twig');
        
        $result = $this->widget->run();
        
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
