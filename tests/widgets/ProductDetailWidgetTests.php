<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ProductDetailWidget;
use yii\base\{Model,
    Widget};
use yii\helpers\Html;
use app\collections\{BaseCollection,
    CollectionInterface};

/**
 * Тестирует класс ProductDetailWidget
 */
class ProductDetailWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductDetailWidget::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
        $this->assertTrue($reflection->hasProperty('imagesWidget'));
        $this->assertTrue($reflection->hasProperty('priceWidget'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setModel
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $model = new class() {};
        
        $widget = new ProductDetailWidget();
        $widget->setModel($model);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $widget = new ProductDetailWidget();
        $widget->setModel($model);
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setImagesWidget
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetImagesWidgetError()
    {
        $class = new class() {};
        
        $widget = new ProductDetailWidget();
        $widget->setImagesWidget($class);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setImagesWidget
     */
    public function testSetImagesWidget()
    {
        $class = new class() extends Widget {};
        
        $widget = new ProductDetailWidget();
        $widget->setImagesWidget($class);
        
        $reflection = new \ReflectionProperty($widget, 'imagesWidget');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Widget::class, $result);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setPriceWidget
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPriceWidgetError()
    {
        $class = new class() {};
        
        $widget = new ProductDetailWidget();
        $widget->setPriceWidget($class);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setPriceWidget
     */
    public function testSetPriceWidget()
    {
        $class = new class() extends Widget {};
        
        $widget = new ProductDetailWidget();
        $widget->setPriceWidget($class);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Widget::class, $result);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     * если пуст ProductDetailWidget::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: model
     */
    public function testRunEmptyModel()
    {
        $widget = new ProductDetailWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     * если пуст ProductDetailWidget::imagesWidget
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: imagesWidget
     */
    public function testRunEmptyImagesWidget()
    {
        $model = new class() extends Model {};
        
        $widget = new ProductDetailWidget();
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $model);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     * если пуст ProductDetailWidget::priceWidget
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: priceWidget
     */
    public function testRunEmptyPriceWidget()
    {
        $model = new class() extends Model {};
        $class = new class() extends Widget {};
        
        $widget = new ProductDetailWidget();
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $model);
        
        $reflection = new \ReflectionProperty($widget, 'imagesWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $class);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     * если пуст ProductDetailWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $model = new class() extends Model {};
        $class = new class() extends Widget {};
        
        $widget = new ProductDetailWidget();
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $model);
        
        $reflection = new \ReflectionProperty($widget, 'imagesWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $class);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $class);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     */
    public function testRun()
    {
        $colors = new class() extends Model {
            public function sort($data) {}
            public function column($data) {
                return ['black', 'red'];
            }
        };
        
        $sizes = new class() extends Model {
            public function sort($data) {}
            public function column($data) {
                return [45, 52.5];
            }
        };
        
        $model = new class() extends Model {
            public $name = 'Name';
            public $description = 'Description';
            public $images = 'test';
            public $price = 85.78;
            public $code = 'TEST';
            public $colors;
            public $sizes;
            
        };
        
        $reflection = new \ReflectionProperty($model, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($model, $colors);
        
        $reflection = new \ReflectionProperty($model, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($model, $sizes);
        
        $priceWidget = new class() extends Widget {
            public $price;
            public function run()
            {
                return $this->price . ' MONEY';
            }
        };
        
        $imagesWidget = new class() extends Widget {
            public $path;
            public $result;
            public function run()
            {
                $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $this->path) . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                foreach ($imagesArray as $image) {
                    if (preg_match('/^(?!thumbn_).+$/', basename($image)) === 1) {
                        $this->result[] = Html::img(\Yii::getAlias('@imagesweb/' . $this->path . '/') . basename($image));
                    }
                }
                return implode('<br/>', $this->result);
            }
        };
        
        $widget = new ProductDetailWidget();
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $model);
        
        $reflection = new \ReflectionProperty($widget, 'imagesWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $imagesWidget);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $priceWidget);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'product-detail.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<h1>Name<\/h1>/', $result);
        $this->assertRegExp('/<p>Description<\/p>/', $result);
        $this->assertRegExp('/<img src=".+" alt=""><br\/>/', $result);
        $this->assertRegExp('/<p><strong>Цвета:<\/strong><\/p>/', $result);
        $this->assertRegExp('/<li>black<\/li>/', $result);
        $this->assertRegExp('/<li>red<\/li>/', $result);
        $this->assertRegExp('/<p><strong>Размеры:<\/strong><\/p>/', $result);
        $this->assertRegExp('/<li>45<\/li>/', $result);
        $this->assertRegExp('/<li>52.5<\/li>/', $result);
        $this->assertRegExp('/<p><strong>Цена:<\/strong> 85.78 MONEY<\/p>/', $result);
        $this->assertRegExp('/<p><strong>Код:<\/strong> TEST<\/p>/', $result);
    }
}
