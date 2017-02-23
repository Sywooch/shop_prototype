<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\ColorsForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ProductsColorsFixture,
    ProductsFixture};

/**
 * Тестирует класс ColorsForm
 */
class ColorsFormTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ColorsForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('color'));
    }
    
    /**
     * Тестирует метод ColorsForm::scenarios
     */
    public function testScenarios()
    {
        $form = new ColorsForm(['scenario'=>ColorsForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        
        $form = new ColorsForm(['scenario'=>ColorsForm::CREATE]);
        $form->attributes = [
            'color'=>'color',
        ];
        
        $reflection = new \ReflectionProperty($form, 'color');
        $result = $reflection->getValue($form);
        $this->assertSame('color', $result);
    }
    
    /**
     * Тестирует метод ColorsForm::rules
     */
    public function testRules()
    {
        $form = new ColorsForm(['scenario'=>ColorsForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new ColorsForm(['scenario'=>ColorsForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new ColorsForm(['scenario'=>ColorsForm::DELETE]);
        $form->attributes = [
            'id'=>102
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new ColorsForm(['scenario'=>ColorsForm::CREATE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new ColorsForm(['scenario'=>ColorsForm::CREATE]);
        $form->attributes = [
            'color'=>self::$dbClass->colors['color_1']['color']
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new ColorsForm(['scenario'=>ColorsForm::CREATE]);
        $form->attributes = [
            'color'=>'color'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
