<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminProductDetailBreadcrumbsWidget;
use yii\base\Model;
use app\models\ProductsModel;

/**
 * Тестирует класс AdminProductDetailBreadcrumbsWidget
 */
class AdminProductDetailBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductDetailBreadcrumbsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('product'));
    }
    
    /**
     * Тестирует метод AdminProductDetailBreadcrumbsWidget::setProduct
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $widget = new AdminProductDetailBreadcrumbsWidget(['product'=>$product]);
    }
    
    /**
     * Тестирует метод AdminProductDetailBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $product = new class() extends Model {
            public $id = 1;
            public $name = 'Shoes Mark Doe';
        };
        
        $widget = new AdminProductDetailBreadcrumbsWidget(['product'=>$product]);
        
        $widget->run();
    }
}

