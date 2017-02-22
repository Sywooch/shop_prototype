<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\SubcategoryForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ProductsFixture,
    SubcategoryFixture};

/**
 * Тестирует класс SubcategoryForm
 */
class SubcategoryFormTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'subcategory'=>SubcategoryFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SubcategoryForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubcategoryForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('seocode'));
        $this->assertTrue($reflection->hasProperty('id_category'));
        $this->assertTrue($reflection->hasProperty('active'));
    }
    
    /**
     * Тестирует метод SubcategoryForm::scenarios
     */
    public function testScenarios()
    {
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::CREATE]);
        $form->attributes = [
            'name'=>'name',
            'seocode'=>'seocode',
            'id_category'=>2,
            'active'=>true,
        ];
        
        $reflection = new \ReflectionProperty($form, 'name');
        $result = $reflection->getValue($form);
        $this->assertSame('name', $result);
        
        $reflection = new \ReflectionProperty($form, 'seocode');
        $result = $reflection->getValue($form);
        $this->assertSame('seocode', $result);
        
        $reflection = new \ReflectionProperty($form, 'id_category');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        
        $reflection = new \ReflectionProperty($form, 'active');
        $result = $reflection->getValue($form);
        $this->assertSame(true, $result);
    }
    
    /**
     * Тестирует метод SubcategoryForm::rules
     */
    public function testRules()
    {
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
        $form->attributes = [
            'id'=>1,
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
        $form->attributes = [
            'id'=>102,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::CREATE]);
        $form->validate();
        
        $this->assertCount(3, $form->errors);
        
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::CREATE]);
        $form->attributes = [
            'name'=>self::$dbClass->subcategory['subcategory_1']['name'],
            'seocode'=>'new-seocode',
            'id_category'=>1,
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::CREATE]);
        $form->attributes = [
            'name'=>'new name',
            'seocode'=>self::$dbClass->subcategory['subcategory_1']['seocode'],
            'id_category'=>1,
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SubcategoryForm(['scenario'=>SubcategoryForm::CREATE]);
        $form->attributes = [
            'name'=>'new name',
            'seocode'=>'new-seocode',
            'id_category'=>1,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
