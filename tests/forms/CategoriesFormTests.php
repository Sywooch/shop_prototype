<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\CategoriesForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;

/**
 * Тестирует класс CategoriesForm
 */
class CategoriesFormTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CategoriesForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('seocode'));
        $this->assertTrue($reflection->hasProperty('active'));
    }
    
    /**
     * Тестирует метод CategoriesForm::scenarios
     */
    public function testScenarios()
    {
        $form = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        
        $form = new CategoriesForm(['scenario'=>CategoriesForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'seocode'=>'name',
            'active'=>true,
        ];
        
        $reflection = new \ReflectionProperty($form, 'name');
        $result = $reflection->getValue($form);
        $this->assertSame('Name', $result);
        
        $reflection = new \ReflectionProperty($form, 'seocode');
        $result = $reflection->getValue($form);
        $this->assertSame('name', $result);
        
        $reflection = new \ReflectionProperty($form, 'active');
        $result = $reflection->getValue($form);
        $this->assertSame(true, $result);
    }
    
    /**
     * Тестирует метод CategoriesForm::rules
     */
    public function testRules()
    {
        $form = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
        $form->attributes = [
            'id'=>101
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new CategoriesForm(['scenario'=>CategoriesForm::CREATE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CategoriesForm(['scenario'=>CategoriesForm::CREATE]);
        $form->attributes = [
            'name'=>self::$dbClass->categories['category_1']['name']
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CategoriesForm(['scenario'=>CategoriesForm::CREATE]);
        $form->attributes = [
            'name'=>'Not exists name'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
