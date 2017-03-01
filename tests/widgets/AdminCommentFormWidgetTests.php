<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminCommentFormWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCommentFormWidget
 */
class AdminCommentFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminCommentFormWidget();
    }
    
    /**
     * Тестирует свойства AdminCommentFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCommentFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('comment'));
        $this->assertTrue($reflection->hasProperty('activeStatuses'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminCommentFormWidget::setComment
     */
    public function testSetComment()
    {
        $comment = new class() extends Model {};
        
        $this->widget->setComment($comment);
        
        $reflection = new \ReflectionProperty($this->widget, 'comment');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод AdminCommentFormWidget::setActiveStatuses
     */
    public function testSetActiveStatuses()
    {
        $activeStatuses = [new class() {}];
        
        $this->widget->setActiveStatuses($activeStatuses);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCommentFormWidget::setForm
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
     * Тестирует метод AdminCommentFormWidget::setTemplate
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
     * Тестирует метод AdminCommentFormWidget::run
     * если пуст AdminCommentFormWidget::comment
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: comment
     */
    public function testRunEmptyComment()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentFormWidget::run
     * если пуст AdminCommentFormWidget::activeStatuses
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: activeStatuses
     */
    public function testRunEmptyActiveStatuses()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'comment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentFormWidget::run
     * если пуст AdminCommentFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'comment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentFormWidget::run
     * если пуст AdminCommentFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'comment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentFormWidget::run
     */
    public function testRun()
    {
        $comment = new class() {
            public $id = 1;
            public $date;
            public $id_name = true;
            public $name;
            public $id_email = true;
            public $email;
            public $id_product = true;
            public $product;
            public $text = 'Text';
            public $active = 1;
            public function __construct()
            {
                $this->date = time();
                $this->name = new class() {
                    public $name = 'Name 1';
                };
                $this->email = new class() {
                    public $email = 'email@email.net';
                };
                $this->product = new class() {
                    public $name = 'name';
                    public $seocode = 'name';
                };
            }
        };
        
        $activeStatuses = [0=>'Not active', 1=>'Active'];
        $form = new class() extends AbstractBaseForm {
            public $id;
            public $text;
            public $active;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'comment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $comment);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $activeStatuses);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-comment-form.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div class="admin-comment-edit-form">#', $result);
        $this->assertRegExp('#<a href=".+">.+</a>#', $result);
        $this->assertRegExp('#Id комментария: [0-9]{1}#', $result);
        $this->assertRegExp('#Дата добавления: .+#', $result);
        $this->assertRegExp('#Комментатор: .+#', $result);
        $this->assertRegExp('#Email: .+@.+#', $result);
        $this->assertRegExp('#<form id="admin-comment-edit-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="1">#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[text\]" rows="[0-9]{1,2}" cols="[0-9]{1,3}">.+</textarea>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[active\]">#', $result);
        $this->assertRegExp('#<option value="0">Not active</option>#', $result);
        $this->assertRegExp('#<option value="1" selected>Active</option>#', $result);
        $this->assertRegExp('#<input type="submit" name="send" value="Сохранить">#', $result);
        $this->assertRegExp('#<input type="submit" name="cancel" value="Отменить">#', $result);
    }
}
