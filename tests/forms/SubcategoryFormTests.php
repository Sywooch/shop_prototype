<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\SubcategoryForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

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
        
        $this->assertTrue($reflection->hasProperty('id'));
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
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
