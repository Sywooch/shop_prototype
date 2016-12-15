<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ProductBreadcrumbsWidget;
use yii\base\Model;
use app\models\ProductsModel;

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
        
        $this->assertTrue($reflection->hasProperty('product'));
    }
    
    /**
     * Тестирует метод ProductBreadcrumbsWidget::setProduct
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $widget = new ProductBreadcrumbsWidget(['product'=>$product]);
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
        
        $product = new class() extends ProductsModel {
            public $name = 'Adidas';
            public $seocode = 'adidas-shoes';
            public $category;
            public $subcategory;
        };
        
        $reflection = new \ReflectionProperty($product, 'category');
        $result = $reflection->setValue($product, $category);
        
        $reflection = new \ReflectionProperty($product, 'subcategory');
        $result = $reflection->setValue($product, $subcategory);
        
        $widget = new ProductBreadcrumbsWidget(['product'=>$product]);
        
        $widget->run();
    }
}

