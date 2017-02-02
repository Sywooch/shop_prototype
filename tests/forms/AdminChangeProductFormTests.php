<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AdminChangeProductForm;

/**
 * Тестирует класс AdminChangeProductForm
 */
class AdminChangeProductFormTests extends TestCase
{
    /**
     * Тестирует свойства AdminChangeProductForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminChangeProductForm::class);
        
        $this->assertTrue($reflection->hasConstant('GET'));
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('code'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('short_description'));
        $this->assertTrue($reflection->hasProperty('description'));
        $this->assertTrue($reflection->hasProperty('price'));
        $this->assertTrue($reflection->hasProperty('images'));
        $this->assertTrue($reflection->hasProperty('id_category'));
        $this->assertTrue($reflection->hasProperty('id_subcategory'));
        $this->assertTrue($reflection->hasProperty('id_brand'));
        $this->assertTrue($reflection->hasProperty('active'));
        $this->assertTrue($reflection->hasProperty('total_products'));
        $this->assertTrue($reflection->hasProperty('seocode'));
        $this->assertTrue($reflection->hasProperty('views'));
    }
    
    /**
     * Тестирует метод AdminChangeProductForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminChangeProductForm(['scenario'=>AdminChangeProductForm::GET]);
        $form->attributes = [
           'id'=>1,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $this->assertSame(1, $reflection->getValue($form));
        
        $form = new AdminChangeProductForm(['scenario'=>AdminChangeProductForm::SAVE]);
        $form->attributes = [
            'id'=>2,
            'code'=>'CODE',
            'name'=>'Name',
            'short_description'=>'Short description',
            'description'=>'Description',
            'price'=>56.45,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_brand'=>2,
            'active'=>true,
            'total_products'=>3,
            'seocode'=>'product-3',
            'views'=>895,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $this->assertSame(2, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'code');
        $this->assertSame('CODE', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'name');
        $this->assertSame('Name', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'short_description');
        $this->assertSame('Short description', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'description');
        $this->assertSame('Description', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'price');
        $this->assertSame(56.45, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'images');
        $this->assertSame('test', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'id_category');
        $this->assertSame(1, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'id_subcategory');
        $this->assertSame(2, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'id_brand');
        $this->assertSame(2, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'active');
        $this->assertSame(true, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'total_products');
        $this->assertSame(3, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'seocode');
        $this->assertSame('product-3', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'views');
        $this->assertSame(895, $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод AdminChangeProductForm::rules
     */
    public function testRules()
    {
        $form = new AdminChangeProductForm(['scenario'=>AdminChangeProductForm::GET]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminChangeProductForm(['scenario'=>AdminChangeProductForm::GET]);
        $form->attributes = [
            'id'=>1,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminChangeProductForm(['scenario'=>AdminChangeProductForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(14, $form->errors);
        
        $form = new AdminChangeProductForm(['scenario'=>AdminChangeProductForm::SAVE]);
        $form->attributes = [
            'id'=>2,
            'code'=>'CODE',
            'name'=>'Name',
            'short_description'=>'Short description',
            'description'=>'Description',
            'price'=>56.45,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>2,
            'id_brand'=>2,
            'active'=>true,
            'total_products'=>3,
            'seocode'=>'product-3',
            'views'=>895,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
