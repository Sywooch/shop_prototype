<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CategoriesBreadcrumbsWidget;
use app\models\{CategoriesModel,
    SubcategoryModel};

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
        
        $widget = new CategoriesBreadcrumbsWidget(['category'=>$category]);
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::setCategory
     */
    public function testSetCategory()
    {
        $category = new class() extends CategoriesModel {};
        
        $widget = new CategoriesBreadcrumbsWidget(['category'=>$category]);
        
        $reflection = new \ReflectionProperty($widget, 'category');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CategoriesModel::class, $result);
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::setSubcategory
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSubcategoryError()
    {
        $category = new class() {};
        
        $widget = new CategoriesBreadcrumbsWidget(['subcategory'=>$category]);
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::setSubcategory
     */
    public function testSetSubcategory()
    {
        $subcategory = new class() extends SubcategoryModel {};
        
        $widget = new CategoriesBreadcrumbsWidget(['subcategory'=>$subcategory]);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(SubcategoryModel::class, $result);
    }
    
    /**
     * Тестирует метод CategoriesBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $category = new class() extends CategoriesModel {
            public $seocode = 'shoes';
            public $name = 'shoes';
        };
        
        $subcategory = new class() extends SubcategoryModel {
            public $seocode = 'sneakers';
            public $name = 'Sneakers';
        };
        
        $widget = new CategoriesBreadcrumbsWidget([
            'category'=>$category,
            'subcategory'=>$subcategory
        ]);
        
        $widget->run();
    }
}

