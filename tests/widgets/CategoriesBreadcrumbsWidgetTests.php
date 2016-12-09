<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CategoriesBreadcrumbsWidget;
use yii\base\Model;

/**
 * Тестирует класс CategoriesBreadcrumbsWidget
 */
class CategoriesBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesBreadcrumbsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::setCategory
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCategoryError()
    {
        $category = new class() {};
        $widget = new CategoriesBreadcrumbsWidget();
        $widget->setCategory($category);
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::setCategory
     */
    public function testSetCategory()
    {
        $category = new class() extends Model {};
        
        $widget = new CategoriesBreadcrumbsWidget();
        $widget->setCategory($category);
        
        $reflection = new \ReflectionProperty($widget, 'category');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::setSubcategory
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSubcategoryError()
    {
        $subcategory = new class() {};
        $widget = new CategoriesBreadcrumbsWidget();
        $widget->setSubcategory($subcategory);
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::setSubcategory
     */
    public function testSetSubcategory()
    {
        $subcategory = new class() extends Model {};
        
        $widget = new CategoriesBreadcrumbsWidget();
        $widget->setSubcategory($subcategory);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $category = new class() extends Model {
            public $seocode = 'shoes';
            public $name = 'shoes';
        };
        $subcategory = new class() extends Model {
            public $seocode = 'sneakers';
            public $name = 'Sneakers';
        };
        
        $widget = new CategoriesBreadcrumbsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'category');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $category);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $subcategory);
        
        $widget->run();
    }
}

