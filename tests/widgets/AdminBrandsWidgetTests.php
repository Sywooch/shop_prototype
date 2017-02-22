<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminBrandsWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\BrandsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminBrandsWidget
 */
class AdminBrandsWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminBrandsWidget();
    }
    
    /**
     * Тестирует свойства AdminBrandsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminBrandsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('brands'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('brandsForm'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminBrandsWidget::setBrands
     */
    public function testSetBrands()
    {
        $brands = [new class() {}];
        
        $this->widget->setBrands($brands);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminBrandsWidget::setHeader
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
     * Тестирует метод AdminBrandsWidget::setBrandsForm
     */
    public function testSetBrandsForm()
    {
        $brandsForm = new class() extends AbstractBaseForm {};
        
        $this->widget->setBrandsForm($brandsForm);
        
        $reflection = new \ReflectionProperty($this->widget, 'brandsForm');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminBrandsWidget::setTemplate
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
     * Тестирует метод AdminBrandsWidget::run
     * если пуст AdminBrandsWidget::brands
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: brands
     */
    public function testRunEmptyBrands()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminBrandsWidget::run
     * если пуст AdminBrandsWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminBrandsWidget::run
     * если пуст AdminBrandsWidget::brandsForm
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: brandsForm
     */
    public function testRunEmptyBrandsForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminBrandsWidget::run
     * если пуст AdminBrandsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'brandsForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminBrandsWidget::run
     */
    public function testRun()
    {
        $brands = [
            new class() {
                public $id = 1;
                public $brand = 'One';
            },
            new class() {
                public $id = 2;
                public $brand = 'Two';
            },
        ];
        
        $form = new class() extends AbstractBaseForm {
            public $id;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $brands);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'brandsForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-brands.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="admin-brand-delete-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#One#', $result);
        $this->assertRegExp('#Two#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
