<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminProductDetailFormWidget;
use app\models\{CurrencyModel,
    ProductsModel};
use app\forms\AdminProductForm;

/**
 * Тестирует класс AdminProductDetailFormWidget
 */
class AdminProductDetailFormWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AdminProductDetailFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductDetailFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('product'));
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setProduct
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setProduct($product);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setProduct
     */
    public function testSetProduct()
    {
        $product = new class() extends ProductsModel {};
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setProduct($product);
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setCategories
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCategoriesError()
    {
        $categories = null;
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setCategories($categories);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setCategories
     */
    public function testSetCategories()
    {
        $categories = [new class() {}];
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setCategories($categories);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setSubcategory
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSubcategoryError()
    {
        $subcategory = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setSubcategory($subcategory);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setSubcategory
     */
    public function testSetSubcategory()
    {
        $subcategory = [new class() {}];
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setSubcategory($subcategory);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setColors
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetColorsError()
    {
        $colors = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setColors($colors);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setColors
     */
    public function testSetColors()
    {
        $colors = [new class() {}];
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setColors($colors);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setSizes
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSizesError()
    {
        $sizes = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setSizes($sizes);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setSizes
     */
    public function testSetSizes()
    {
        $sizes = [new class() {}];
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setSizes($sizes);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setBrands
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetBrandsError()
    {
        $brands = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setBrands($brands);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setBrands
     */
    public function testSetBrands()
    {
        $brands = [new class() {}];
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setBrands($brands);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AdminProductForm {};
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(AdminProductForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminProductDetailFormWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     * если пуст AdminProductDetailFormWidget::product
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: product
     */
    public function testRunEmptyPurchase()
    {
        $widget = new AdminProductDetailFormWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     * если пуст AdminProductDetailFormWidget::categories
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: categories
     */
    public function testRunEmptyCategories()
    {
        $mock = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     * если пуст AdminProductDetailFormWidget::subcategory
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: subcategory
     */
    public function testRunEmptySubcategory()
    {
        $mock = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     * если пуст AdminProductDetailFormWidget::colors
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: colors
     */
    public function testRunEmptyColors()
    {
        $mock = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     * если пуст AdminProductDetailFormWidget::sizes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sizes
     */
    public function testRunEmptySizes()
    {
        $mock = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     * если пуст AdminProductDetailFormWidget::brands
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: brands
     */
    public function testRunEmptyBrands()
    {
        $mock = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     * если пуст AdminProductDetailFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     * если пуст AdminProductDetailFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AdminProductDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminProductDetailFormWidget::run
     */
    public function testRun()
    {
        $categories = [1=>'Menswear', 2=>'Mensfootwear'];
        $subcategory = [1=>'Coat', 2=>'Sneakers'];
        $colors = [1=>'black', 2=>'gray'];
        $sizes = [1=>50, 2=>45.5];
        $brands = [1=>'Adidas', 2=>'Nike'];
        $form = new class() extends AdminProductForm {};
        
        $product = new class() {
            public $id = 1;
            public $code = 'HJFER-9';
            public $name = 'Name 1';
            public $short_description = 'Short description 1';
            public $description = 'Description 1';
            public $price = 1658.89;
            public $images = 'test';
            public $id_category = 1;
            public $id_subcategory = 2;
            public $colors;
            public $sizes;
            public $id_brand = 1;
            public $active = true;
            public $total_products = 105;
            public $seocode = 'name-1';
            public $views = 561;
            public function __construct()
            {
                $this->colors = [
                    new class() {
                        public $id = 1;
                        public $color = 'black';
                    },
                ];
                $this->sizes = [
                    new class() {
                        public $id = 2;
                        public $size = 45.5;
                    },
                ];
            }
        };
        
        $widget = new AdminProductDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $product);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $categories);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $subcategory);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $colors);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $sizes);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $brands);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-product-detail-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="admin-product-change-form">#', $result);
        $this->assertRegExp('#<form id="admin-products-detail-send-form-[0-9]{1}" action=".+" method="POST" enctype="multipart/form-data">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[code\]" value=".+">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]" value=".+">#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[short_description\]" rows="[0-9]{2}" cols="[0-9]{2}">.+</textarea>#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[description\]" rows="[0-9]{2}" cols="[0-9]{2}">.+</textarea>#', $result);
        $this->assertRegExp('#<input type="number" id=".+" class="form-control" name=".+\[price\]" value=".+" step="0.01" min="0">#', $result);
        $this->assertRegExp('#<img src=".+" height="50" alt="">#', $result);
        $this->assertRegExp('#<input type="file" id=".+" name=".+\[images\]" multiple accept="image\/\*">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_category\]" data-href=".+">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_subcategory\]">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_colors\]\[\]" multiple size="[0-9]{1}">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_sizes\]\[\]" multiple size="[0-9]{1}">#', $result);
        $this->assertRegExp('#<option value="[0-9]{1}" selected>.+</option>#', $result);
        $this->assertRegExp('#<option value="[0-9]{1}">.+</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_brand\]">#', $result);
        $this->assertRegExp('#<label><input type="checkbox" id=".+" name=".+\[active\]" value="1" checked> Active</label>#', $result);
        $this->assertRegExp('#<input type="number" id=".+" class="form-control" name=".+\[total_products\]" value="[0-9]{1,3}" step="1" min="0">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[seocode\]" value=".+">#', $result);
        $this->assertRegExp('#<input type="number" id=".+" class="form-control" name=".+\[views\]" value="[0-9]{1,3}" step="1" min="0">#', $result);
        $this->assertRegExp('#<input type="submit" name="send" value="Сохранить">#', $result);
        $this->assertRegExp('#<input type="submit" name="cancel" value="Отменить">#', $result);
    }
}
