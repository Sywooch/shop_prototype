<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminAddProductFormWidget;
use app\forms\{AbstractBaseForm,
    AdminProductForm};

/**
 * Тестирует класс AdminAddProductFormWidget
 */
class AdminAddProductFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminAddProductFormWidget();
    }
    
    /**
     * Тестирует свойства AdminAddProductFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminAddProductFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::setCategories
     */
    public function testSetCategories()
    {
        $categories = [new class() {}];
        
        $this->widget->setCategories($categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::setSubcategory
     */
    public function testSetSubcategory()
    {
        $subcategory = [new class() {}];
        
        $this->widget->setSubcategory($subcategory);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::setColors
     */
    public function testSetColors()
    {
        $colors = [new class() {}];
        
        $this->widget->setColors($colors);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::setSizes
     */
    public function testSetSizes()
    {
        $sizes = [new class() {}];
        
        $this->widget->setSizes($sizes);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::setBrands
     */
    public function testSetBrands()
    {
        $brands = [new class() {}];
        
        $this->widget->setBrands($brands);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::setForm
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
     * Тестирует метод AdminAddProductFormWidget::setTemplate
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
     * Тестирует метод AdminAddProductFormWidget::run
     * если пуст AdminAddProductFormWidget::categories
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: categories
     */
    public function testRunEmptyCategories()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::run
     * если пуст AdminAddProductFormWidget::subcategory
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: subcategory
     */
    public function testRunEmptySubcategory()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::run
     * если пуст AdminAddProductFormWidget::colors
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: colors
     */
    public function testRunEmptyColors()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::run
     * если пуст AdminAddProductFormWidget::sizes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sizes
     */
    public function testRunEmptySizes()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::run
     * если пуст AdminAddProductFormWidget::brands
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: brands
     */
    public function testRunEmptyBrands()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::run
     * если пуст AdminAddProductFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::run
     * если пуст AdminAddProductFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminAddProductFormWidget::run
     */
    public function testRun()
    {
        $categories = [1=>'Menswear', 2=>'Mensfootwear'];
        $subcategory = [1=>'Coat', 2=>'Sneakers'];
        $colors = [1=>'black', 2=>'gray'];
        $sizes = [1=>50, 2=>45.5];
        $brands = [1=>'Adidas', 2=>'Nike'];
        $form = new class() extends AdminProductForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $subcategory);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $colors);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $sizes);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $brands);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-add-product-form.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<form id="admin-add-product-form" action=".+" method="POST" enctype="multipart/form-data">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[code\]">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]">#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[short_description\]" rows="[0-9]{1,2}" cols="[0-9]{1,2}"></textarea>#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[description\]" rows="[0-9]{1,2}" cols="[0-9]{1,2}"></textarea>#', $result);
        $this->assertRegExp('#<input type="number" id=".+" class="form-control" name=".+\[price\]" step="0.01" min="0">#', $result);
        $this->assertRegExp('#<input type="file" id=".+" name=".+\[images\]\[\]" multiple accept="image\/\*">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_category\]" data-href=".+" data-disabled>#', $result);
        $this->assertRegExp('#<option value="1">Menswear</option>#', $result);
        $this->assertRegExp('#<option value="2">Mensfootwear</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_subcategory\]" data-disabled>#', $result);
        $this->assertRegExp('#<option value="1">Coat</option>#', $result);
        $this->assertRegExp('#<option value="2">Sneakers</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_colors\]\[\]" multiple size="4">#', $result);
        $this->assertRegExp('#<option value="1">black</option>#', $result);
        $this->assertRegExp('#<option value="2">gray</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_sizes\]\[\]" multiple size="4">#', $result);
        $this->assertRegExp('#<option value="1">50</option>#', $result);
        $this->assertRegExp('#<option value="2">45.5</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_brand\]" data-disabled>#', $result);
        $this->assertRegExp('#<option value="1">Adidas</option>#', $result);
        $this->assertRegExp('#<option value="2">Nike</option>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" id=".+" name=".+\[active\]" value="1">.+</label>#', $result);
        $this->assertRegExp('#<input type="number" id=".+" class="form-control" name=".+\[total_products\]" step="1" min="0">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[seocode\]">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[related\]">#', $result);
        $this->assertRegExp('#<input type="submit" name="send" value="Сохранить">#', $result);
    }
}
