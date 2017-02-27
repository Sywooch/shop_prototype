<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCommentsWidget;
use app\models\CurrencyModel;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCommentsWidget
 */
class AdminCommentsWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminCommentsWidget();
    }
    
    /**
     * Тестирует свойства AdminCommentsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCommentsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('comments'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminCommentsWidget::setComments
     */
    public function testSetComments()
    {
        $comments = [new class() {}];
        
        $this->widget->setComments($comments);
        
        $reflection = new \ReflectionProperty($this->widget, 'comments');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCommentsWidget::setForm
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
     * Тестирует метод AdminCommentsWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $this->widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminCommentsWidget::setTemplate
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
     * Тестирует метод AdminCommentsWidget::run
     * если пуст AdminCommentsWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentsWidget::run
     * если пуст AdminCommentsWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentsWidget::run
     * если пуст AdminCommentsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentsWidget::run
     * если нет комментариев
     */
    public function testRunEmptyOrders()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $this->widget = new AdminCommentsWidget();
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-comments.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Нет комментариев</p>#', $result);
    }
    
    /**
     * Тестирует метод AdminCommentsWidget::run
     */
    public function testRun()
    {
        $form = new class() extends AbstractBaseForm {
            public $id;
        };
        
        $comments = [
            new class() {
                public $id = 1;
                public $date;
                public $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
                public $id_name = 1;
                public $name;
                public $id_email = 1;
                public $email;
                public $id_product = 1;
                public $product;
                public $active = 1;
                public function __construct()
                {
                    $this->date = time();
                    $this->name = new class()
                    {
                        public $name = 'Name 1';
                    };
                    $this->email = new class()
                    {
                        public $email = 'email1@mail.com';
                    };
                    $this->product = new class()
                    {
                        public $name = 'Product 1';
                        public $seocode = 'product-1';
                    };
                }
            },
            new class() {
                public $id = 2;
                public $date;
                public $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
                public $id_name = 2;
                public $name;
                public $id_email = 2;
                public $email;
                public $id_product = 2;
                public $product;
                public $active = 2;
                public function __construct()
                {
                    $this->date = time();
                    $this->name = new class()
                    {
                        public $name = 'Name 2';
                    };
                    $this->email = new class()
                    {
                        public $email = 'email2@mail.com';
                    };
                    $this->product = new class()
                    {
                        public $name = 'Product 2';
                        public $seocode = 'product-2';
                    };
                }
            },
        ];
        
        $reflection = new \ReflectionProperty($this->widget, 'comments');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $comments);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-comments.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<div class="admin-comments-previous-data">#', $result);
        $this->assertRegExp('#<a href="../vendor/phpunit/phpunit/product-1">Product 1</a>#', $result);
        $this->assertRegExp('#Id комментария: [0-9]{1,3}#', $result);
        $this->assertRegExp('#Дата добавления: .+#', $result);
        $this->assertRegExp('#Комментатор: .+#', $result);
        $this->assertRegExp('#Email: .+#', $result);
        $this->assertRegExp('#Текст: .+#', $result);
        $this->assertRegExp('#Активен: .+#', $result);
        $this->assertRegExp('#<form id="admin-comment-detail-get-form-[0-9]{1,3}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="1">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
        $this->assertRegExp('#<form id="admin-comment-detail-delete-form-[0-9]{1,3}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Удалить">#', $result);
    }
}
