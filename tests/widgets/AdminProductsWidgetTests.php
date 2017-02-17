<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminProductsWidget;
use app\models\CurrencyModel;
use app\forms\AdminProductForm;

/**
 * Тестирует класс AdminProductsWidget
 */
class AdminProductsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AdminProductsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('products'));
        $this->assertTrue($reflection->hasProperty('currency'));
        //$this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminProductsWidget::setProducts
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductsError()
    {
        $products = new class() {};
        
        $widget = new AdminProductsWidget();
        $widget->setProducts($products);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::setProducts
     */
    public function testSetProducts()
    {
        $products = [new class() {}];
        
        $widget = new AdminProductsWidget();
        $widget->setProducts($products);
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::setCurrency
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new AdminProductsWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new AdminProductsWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    /*public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AdminProductsWidget();
        $widget->setForm($form);
    }*/
    
    /**
     * Тестирует метод AdminProductsWidget::setForm
     */
    /*public function testSetForm()
    {
        $form = new class() extends AdminProductForm {};
        
        $widget = new AdminProductsWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(AdminProductForm::class, $result);
    }*/
    
    /**
     * Тестирует метод AdminProductsWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AdminProductsWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AdminProductsWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminProductsWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminProductsWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::run
     * если пуст AdminProductsWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $widget = new AdminProductsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsWidget::run
     * если пуст AdminProductsWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    /*public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new AdminProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }*/
    
    /**
     * Тестирует метод AdminProductsWidget::run
     * если пуст AdminProductsWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AdminProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        /*$reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);*/
        
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsWidget::run
     * если пуст AdminProductsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AdminProductsWidget();
       
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        /*$reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);*/
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsWidget::run
     * если нет товаров
     */
    public function testRunEmptyOrders()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $form = new class() extends AdminProductForm {};
        
        $widget = new AdminProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        /*$reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);*/
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-products.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Нет товаров</p>#', $result);
    }
    
    /**
     * Тестирует метод AdminProductsWidget::run
     */
    public function testRun()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        //$form = new class() extends AdminProductForm {};
        
        $products = [
            new class() {
                public $id = 2;
                public $date;
                public $code = 'CODE1';
                public $name = 'Product 1';
                public $short_description = 'Short description 1';
                public $description = 'Sescription 1';
                public $price = 56.85;
                public $colors = [['id'=>1, 'color'=>'black'], ['id'=>2, 'color'=>'green']];
                public $sizes = [['id'=>1, 'size'=>45], ['id'=>2, 'size'=>35.5]];
                public $image = 'test';
                public $category;
                public $subcategory;
                public $brand;
                public $active = true;
                public $total_products = 236;
                public $seocode = 'product-1';
                public $views = 103;
                public function __construct()
                {
                    $this->date = time();
                    $this->category = new class() {
                        public $name = 'Category 1';
                    };
                    $this->subcategory = new class() {
                        public $name = 'Subcategory 1';
                    };
                    $this->brand = new class() {
                        public $brand = 'Brand 1';
                    };
                }
            },
            new class() {
                public $id = 3;
                public $date;
                public $code = 'CODE3';
                public $name = 'Product 3';
                public $short_description = 'Short description 3';
                public $description = 'Sescription 3';
                public $price = 156.85;
                public $colors = [['id'=>1, 'color'=>'black'], ['id'=>2, 'color'=>'green']];
                public $sizes = [['id'=>1, 'size'=>45], ['id'=>2, 'size'=>35.5]];
                public $images = 'test';
                public $category;
                public $subcategory;
                public $brand;
                public $active = true;
                public $total_products = 236;
                public $seocode = 'product-3';
                public $views = 103;
                public function __construct()
                {
                    $this->date = time();
                    $this->category = new class() {
                        public $name = 'Category 3';
                    };
                    $this->subcategory = new class() {
                        public $name = 'Subcategory 3';
                    };
                    $this->brand = new class() {
                        public $brand = 'Brand 3';
                    };
                }
            },
        ];
        
        $widget = new AdminProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        /*$reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);*/
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-products.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<ol class="admin-products">#', $result);
        $this->assertRegExp('#<div class="admin-product-previous-data">#', $result);
        $this->assertRegExp('#<a href=".+">Product [0-9]{1}</a>#', $result);
        $this->assertRegExp('#Краткое описание:  Short description 3#', $result);
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#Id товара: [0-9]{1}#', $result);
        $this->assertRegExp(sprintf('#Дата добавления: %s#', \Yii::$app->formatter->asDate(time())), $result);
        $this->assertRegExp('#Код: CODE[0-9]{1}#', $result);
        $this->assertRegExp('#Описание: Sescription [0-9]{1}#', $result);
        $this->assertRegExp('#Цена: .+ MONEY#', $result);
        $this->assertRegExp('#Цвета: .+#', $result);
        $this->assertRegExp('#Размеры: .+#', $result);
        $this->assertRegExp('#Категория: Category [0-9]{1}#', $result);
        $this->assertRegExp('#Подкатегория: Subcategory [0-9]{1}#', $result);
        $this->assertRegExp('#Бренд: Brand [0-9]{1}#', $result);
        $this->assertRegExp('#Активен: Активен#', $result);
        $this->assertRegExp('#Количество товаров: [0-9]{3}#', $result);
        $this->assertRegExp('#Сеокод: product-[0-9]{1}#', $result);
        $this->assertRegExp('#Просмотров: [0-9]{3}#', $result);
        $this->assertRegExp('#<br><a href=".+"><strong>Изменить</strong></a>#', $result);
    }
}
