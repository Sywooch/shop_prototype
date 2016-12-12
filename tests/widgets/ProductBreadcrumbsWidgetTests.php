<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ProductBreadcrumbsWidget;
use yii\base\Model;

/**
 * Тестирует класс ProductBreadcrumbsWidget
 */
class ProductBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductBreadcrumbsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод ProductBreadcrumbsWidget::setModel
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $model = new class() {};
        
        $widget = new ProductBreadcrumbsWidget(['model'=>$model]);
    }
    
    /**
     * Тестирует метод ProductBreadcrumbsWidget::run
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
        
        $model = new class() extends Model {
            public $name = 'Adidas';
            public $seocode = 'adidas-shoes';
            public $category;
            public $subcategory;
        };
        
        $reflection = new \ReflectionProperty($model, 'category');
        $result = $reflection->setValue($model, $category);
        
        $reflection = new \ReflectionProperty($model, 'subcategory');
        $result = $reflection->setValue($model, $subcategory);
        
        $widget = new ProductBreadcrumbsWidget(['model'=>$model]);
        
        $widget->run();
    }
}

