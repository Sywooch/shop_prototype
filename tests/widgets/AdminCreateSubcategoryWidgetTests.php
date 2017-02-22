<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCreateSubcategoryWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCreateSubcategoryWidget
 */
class AdminCreateSubcategoryWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminCreateSubcategoryWidget();
    }
    
    /**
     * Тестирует свойства AdminCreateSubcategoryWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCreateSubcategoryWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminCreateSubcategoryWidget::setForm
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
     * Тестирует метод AdminCreateSubcategoryWidget::setCategories
     */
    public function testSetCategories()
    {
        $categories = [new class() {}];
        
        $this->widget->setCategories($categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCreateSubcategoryWidget::setHeader
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
     * Тестирует метод AdminCreateSubcategoryWidget::setTemplate
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
     * Тестирует метод AdminCreateSubcategoryWidget::run
     * если пуст AdminCreateSubcategoryWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCreateSubcategoryWidget::run
     * если пуст AdminCreateSubcategoryWidget::categories
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: categories
     */
    public function testRunEmptyCategories()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCreateSubcategoryWidget::run
     * если пуст AdminCreateSubcategoryWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCreateSubcategoryWidget::run
     * если пуст AdminCreateSubcategoryWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCreateSubcategoryWidget::run
     */
    public function testRun()
    {
        $form = new class() extends AbstractBaseForm {
            public $name;
            public $seocode;
            public $id_category;
            public $active;
        };
        
        $categories = [
            new class() {
                public $id = 1;
                public $name = 'One';
            },
            new class() {
                public $id = 2;
                public $name = 'Two';
            },
        ];
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-create-subcategory.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="subcategory-create-form" action="..+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_category\]">#', $result);
        $this->assertRegExp('#<option value="0">------------------------</option>#', $result);
        $this->assertRegExp('#<option value="1">One</option>#', $result);
        $this->assertRegExp('#<option value="2">Two</option>#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[seocode\]">#', $result);
        $this->assertRegExp('#<label><input type="checkbox" id=".+" name=".+\[active\]" value="1"> Active</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Создать">#', $result);
    }
}
