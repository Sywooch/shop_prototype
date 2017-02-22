<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminColorsWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminColorsWidget
 */
class AdminColorsWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminColorsWidget();
    }
    
    /**
     * Тестирует свойства AdminColorsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminColorsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('colorsForm'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminColorsWidget::setColors
     */
    public function testSetColors()
    {
        $colors = [new class() {}];
        
        $this->widget->setColors($colors);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminColorsWidget::setHeader
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
     * Тестирует метод AdminColorsWidget::setColorsForm
     */
    public function testSetColorsForm()
    {
        $colorsForm = new class() extends AbstractBaseForm {};
        
        $this->widget->setColorsForm($colorsForm);
        
        $reflection = new \ReflectionProperty($this->widget, 'colorsForm');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminColorsWidget::setTemplate
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
     * Тестирует метод AdminColorsWidget::run
     * если пуст AdminColorsWidget::colors
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: colors
     */
    public function testRunEmptyColors()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminColorsWidget::run
     * если пуст AdminColorsWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminColorsWidget::run
     * если пуст AdminColorsWidget::colorsForm
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: colorsForm
     */
    public function testRunEmptyColorsForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminColorsWidget::run
     * если пуст AdminColorsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'colorsForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminColorsWidget::run
     */
    public function testRun()
    {
        $colors = [
            new class() {
                public $id = 1;
                public $color = 'One';
            },
            new class() {
                public $id = 2;
                public $color = 'Two';
            },
        ];
        
        $form = new class() extends AbstractBaseForm {
            public $id;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $colors);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'colorsForm');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-colors.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="admin-color-delete-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#One#', $result);
        $this->assertRegExp('#Two#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
