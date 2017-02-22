<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\BrandsForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    ProductsFixture};

/**
 * Тестирует класс BrandsForm
 */
class BrandsFormTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'brands'=>BrandsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства BrandsForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BrandsForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('brand'));
    }
    
    /**
     * Тестирует метод BrandsForm::scenarios
     */
    public function testScenarios()
    {
        $form = new BrandsForm(['scenario'=>BrandsForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        
        $form = new BrandsForm(['scenario'=>BrandsForm::CREATE]);
        $form->attributes = [
            'brand'=>'brand',
        ];
        
        $reflection = new \ReflectionProperty($form, 'brand');
        $result = $reflection->getValue($form);
        $this->assertSame('brand', $result);
    }
    
    /**
     * Тестирует метод BrandsForm::rules
     */
    public function testRules()
    {
        $form = new BrandsForm(['scenario'=>BrandsForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new BrandsForm(['scenario'=>BrandsForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new BrandsForm(['scenario'=>BrandsForm::DELETE]);
        $form->attributes = [
            'id'=>102
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new BrandsForm(['scenario'=>BrandsForm::CREATE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new BrandsForm(['scenario'=>BrandsForm::CREATE]);
        $form->attributes = [
            'brand'=>self::$dbClass->brands['brand_1']['brand']
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new BrandsForm(['scenario'=>BrandsForm::CREATE]);
        $form->attributes = [
            'brand'=>'brand'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
