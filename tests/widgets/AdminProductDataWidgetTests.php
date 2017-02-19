<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminProductDataWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\models\{CurrencyInterface,
    CurrencyModel};
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminProductDataWidget
 */
class AdminProductDataWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminProductDataWidget();
    }
    
    /**
     * Тестирует свойства AdminProductDataWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductDataWidget::class);
        
        $this->assertTrue($reflection->hasProperty('productsModel'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminProductDataWidget::setProductsModel
     */
    public function testSetProductsModel()
    {
        $productsModel = new class() extends Model {};
        
        $this->widget->setProductsModel($productsModel);
        
        $reflection = new \ReflectionProperty($this->widget, 'productsModel');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductDataWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $this->widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(CurrencyInterface::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductDataWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($form);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductDataWidget::setTemplate
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
     * Тестирует метод AdminProductDataWidget::run
     * если пуст AdminProductDataWidget::productsModel
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: productsModel
     */
    public function testRunEmptyProductsModel()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDataWidget::run
     * если пуст AdminProductDataWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'productsModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDataWidget::run
     * если пуст AdminProductDataWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'productsModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDataWidget::run
     * если пуст AdminProductDataWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'productsModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDataWidget::run
     */
    public function testRun()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $form = new class() extends AbstractBaseForm {
            public $id;
        };
        
        $productsModel = new class() extends Model {
            public $id = 2;
            public $date;
            public $code = 'CODE';
            public $seocode = 'product-1';
            public $name = 'Product 1';
            public $short_description = 'Short description';
            public $description = 'Description';
            public $price = 568.78;
            public $colors;
            public $sizes;
            public $images = 'test';
            public $category;
            public $subcategory;
            public $brand;
            public $active = true;
            public $total_products = 568;
            public $views = 5698;
            public function __construct()
            {
                $this->date = time();
                $this->colors = [['id'=>1, 'color'=>'black'], ['id'=>2, 'color'=>'gray']];
                $this->sizes = [['id'=>1, 'size'=>46], ['id'=>2, 'size'=>35]];
                $this->category = new class() {
                    public $name = 'category';
                };
                $this->subcategory = new class() {
                    public $name = 'subcategory';
                };
                $this->brand = new class() {
                    public $brand = 'brand';
                };
            }
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'productsModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $productsModel);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-product-data.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div class="admin-product-previous-data">#', $result);
        $this->assertRegExp('#<a href=".+">Product 1</a>#', $result);
        $this->assertRegExp('#Краткое описание:  Short description#', $result);
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#Id товара: 2#', $result);
        $this->assertRegExp('#Дата добавления: .+#', $result);
        $this->assertRegExp('#Код: CODE#', $result);
        $this->assertRegExp('#Описание: Description#', $result);
        $this->assertRegExp('#Цена: 1188,75 MONEY#', $result);
        $this->assertRegExp('#Цвета: black, gray#', $result);
        $this->assertRegExp('#Размеры: 46, 35#', $result);
        $this->assertRegExp('#Категория: category#', $result);
        $this->assertRegExp('#Подкатегория: subcategory#', $result);
        $this->assertRegExp('#Бренд: brand#', $result);
        $this->assertRegExp('#Активен: Активен#', $result);
        $this->assertRegExp('#Количество товаров: 568#', $result);
        $this->assertRegExp('#Сеокод: product-1#', $result);
        $this->assertRegExp('#Просмотров: 5698#', $result);
        $this->assertRegExp('#<form id="admin-product-detail-get-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
