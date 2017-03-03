<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\CurrencyForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс CurrencyForm
 */
class CurrencyFormTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CurrencyForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        $this->assertTrue($reflection->hasConstant('BASE_CHANGE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('code'));
        $this->assertTrue($reflection->hasProperty('main'));
    }
    
    /**
     * Тестирует метод CurrencyForm::scenarios
     */
    public function testScenarios()
    {
        $form = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $this->assertSame(2, $form->id);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
        $form->attributes = [
            'code'=>'CODE',
            'main'=>1,
        ];
        
        $this->assertSame('CODE', $form->code);
        $this->assertSame(1, $form->main);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::BASE_CHANGE]);
        $form->attributes = [
            'id'=>45,
            'main'=>1,
        ];
        
        $this->assertSame(45, $form->id);
        $this->assertSame(1, $form->main);
    }
    
    /**
     * Тестирует метод CurrencyForm::rules
     */
    public function testRules()
    {
        $form = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
        $form->attributes = [
            'id'=>2
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
        $form->attributes = [
            'code'=>'CODE'
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
        $form->attributes = [
            'code'=>self::$dbClass->currency['currency_1']['code']
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
        $form->attributes = [
            'code'=>'COD'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::BASE_CHANGE]);
        $form->validate();
        
        $this->assertCount(2, $form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::BASE_CHANGE]);
        $form->attributes = [
            'id'=>23,
            'main'=>1,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
