<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCategoriesWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCategoriesWidget
 */
class AdminCategoriesWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminCategoriesWidget();
    }
    
    /**
     * Тестирует свойства AdminCategoriesWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCategoriesWidget::class);
        
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('categoriesForm'));
        $this->assertTrue($reflection->hasProperty('subcategoryForm'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminCategoriesWidget::setCategories
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
     * Тестирует метод AdminCategoriesWidget::setHeader
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
     * Тестирует метод AdminCategoriesWidget::setCategoriesForm
     */
    public function testSetCategoriesForm()
    {
        $categoriesForm = new class() extends AbstractBaseForm {};
        
        $this->widget->setCategoriesForm($categoriesForm);
        
        $reflection = new \ReflectionProperty($this->widget, 'categoriesForm');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminCategoriesWidget::setSubcategoryForm
     */
    public function testSetSubcategoryForm()
    {
        $subcategoryForm = new class() extends AbstractBaseForm {};
        
        $this->widget->setSubcategoryForm($subcategoryForm);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategoryForm');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminCategoriesWidget::setTemplate
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
     * Тестирует метод AdminCategoriesWidget::run
     * если пуст AdminCategoriesWidget::categories
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: categories
     */
    public function testRunEmptyCategories()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCategoriesWidget::run
     * если пуст AdminCategoriesWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCategoriesWidget::run
     * если пуст AdminCategoriesWidget::categoriesForm
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: categoriesForm
     */
    public function testRunEmptyCategoriesForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCategoriesWidget::run
     * если пуст AdminCategoriesWidget::subcategoryForm
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: subcategoryForm
     */
    public function testRunEmptySubcategoryForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'categoriesForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCategoriesWidget::run
     * если пуст AdminCategoriesWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'categoriesForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategoryForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCategoriesWidget::run
     */
    public function testRun()
    {
        $categories = [
            new class() {
                public $id = 1;
                public $name = 'One';
                public $active = 0;
                public $subcategory;
                public function __construct()
                {
                    $this->subcategory = [
                        new class() {
                            public $id = 1;
                            public $name = 'One one';
                            public $active = 1;
                        },
                        new class() {
                            public $id = 2;
                            public $name = 'One two';
                            public $active = 1;
                        },
                    ];
                }
            },
            new class() {
                public $id = 2;
                public $name = 'Two';
                public $active = 1;
                public $subcategory;
                public function __construct()
                {
                    $this->subcategory = [
                        new class() {
                            public $id = 3;
                            public $name = 'Two one';
                            public $active = 0;
                        },
                        new class() {
                            public $id = 4;
                            public $name = 'Two two';
                            public $active = 1;
                        },
                    ];
                }
            },
        ];
        
        $form = new class() extends AbstractBaseForm {
            public $id;
            public $active;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'categoriesForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategoryForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-categories.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#One#', $result);
        $this->assertRegExp('#One one#', $result);
        $this->assertRegExp('#Two#', $result);
        $this->assertRegExp('#Two two#', $result);
        $this->assertRegExp('#<form id="admin-category-delete-form-[0-9]{1}" action="..+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Удалить">#', $result);
        $this->assertRegExp('#<form id="admin-subcategory-delete-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<form id="admin-category-change-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<label class="control-label" for=".+">Active</label>#', $result);
        $this->assertRegExp('#<input type="checkbox" id=".+" class="form-control" name=".+\[active\]" value="1" checked>#', $result);
        $this->assertRegExp('#<form id="admin-subcategory-change-form-[0-9]{1}" action=".+" method="POST">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
