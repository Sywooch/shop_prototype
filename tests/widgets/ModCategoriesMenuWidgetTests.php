<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ModCategoriesMenuWidget;
use app\collections\{BaseCollection,
    CollectionInterface};
use app\controllers\ProductsListController;
use yii\helpers\Url;

/**
 * Тестирует класс ModCategoriesMenuWidget
 */
class ModCategoriesMenuWidgetTests extends TestCase
{
    private $widget;
    
    public static function setUpBeforeClass()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
    }
    
    public function setUp()
    {
        $this->widget = new ModCategoriesMenuWidget();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ModCategoriesMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('template'));
        $this->assertTrue($reflection->hasProperty('currentUrl'));
        $this->assertTrue($reflection->hasProperty('rootRoute'));
    }
    
    /**
     * Тестирует метод ModCategoriesMenuWidget::setCategories
     */
    public function testSetCategories()
    {
        $this->widget->setCategories([new class() {}]);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод ModCategoriesMenuWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $this->widget->setTemplate('some.twig');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ModCategoriesMenuWidget::setCurrentUrl
     */
    public function testSetCurrentUrl()
    {
        $this->widget->setCurrentUrl('/category-2');
        
        $reflection = new \ReflectionProperty($this->widget, 'currentUrl');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ModCategoriesMenuWidget::setRootRoute
     */
    public function testSetRootRoute()
    {
        $this->widget->setRootRoute('/category-2');
        
        $reflection = new \ReflectionProperty($this->widget, 'rootRoute');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ModCategoriesMenuWidget::run
     */
    public function testRun()
    {
        $categories = [
            new class() {
                public $active = true;
                public $name = 'Category 1';
                public $seocode = 'category-1';
                public $subcategory;
                public function __construct()
                {
                    $this->subcategory = [
                        new class() {
                            public $active = true;
                            public $name = 'Subcategory 1';
                            public $seocode = 'subcategory-1';
                        },
                    ];
                }
            },
        ];
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'categories-menu.twig');
        
        $reflection = new \ReflectionProperty($this->widget, 'currentUrl');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, '../vendor/phpunit/phpunit/category-1');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<nav id="categories-menu-container">#', $result);
        $this->assertRegExp('#<ul class="categories-menu">#', $result);
        $this->assertRegExp('#<li><a href=".+">Весь каталог</a>#', $result);
        $this->assertRegExp('#<li><span class="category-button">Category 1</span><span class="menu-marker">&rsaquo;</span>#', $result);
        $this->assertRegExp('#<ul class="subcategory-menu disable">#', $result);
        $this->assertRegExp('#<li class="active"><a href=".+">Все</a></li>#', $result);
        $this->assertRegExp('#<li><a href=".+">Subcategory 1</a></li>#', $result);
    }
}
