<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\SizesForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ProductsSizesFixture,
    ProductsFixture};

/**
 * Тестирует класс SizesForm
 */
class SizesFormTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SizesForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('size'));
    }
    
    /**
     * Тестирует метод SizesForm::scenarios
     */
    public function testScenarios()
    {
        $form = new SizesForm(['scenario'=>SizesForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        
        $form = new SizesForm(['scenario'=>SizesForm::CREATE]);
        $form->attributes = [
            'size'=>'size',
        ];
        
        $reflection = new \ReflectionProperty($form, 'size');
        $result = $reflection->getValue($form);
        $this->assertSame('size', $result);
    }
    
    /**
     * Тестирует метод SizesForm::rules
     */
    public function testRules()
    {
        $form = new SizesForm(['scenario'=>SizesForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SizesForm(['scenario'=>SizesForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SizesForm(['scenario'=>SizesForm::DELETE]);
        $form->attributes = [
            'id'=>102
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new SizesForm(['scenario'=>SizesForm::CREATE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SizesForm(['scenario'=>SizesForm::CREATE]);
        $form->attributes = [
            'size'=>self::$dbClass->sizes['size_1']['size']
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SizesForm(['scenario'=>SizesForm::CREATE]);
        $form->attributes = [
            'size'=>'size'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
