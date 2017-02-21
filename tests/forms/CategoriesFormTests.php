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
        
        $this->assertTrue($reflection->hasProperty('id'));
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
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
