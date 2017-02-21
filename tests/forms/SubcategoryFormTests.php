<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\SubcategoryForm;

/**
 * Тестирует класс SubcategoryForm
 */
class SubcategoryFormTests extends TestCase
{
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
            'id'=>3,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
