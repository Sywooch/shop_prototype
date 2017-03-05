<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\PaymentsForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;

/**
 * Тестирует класс PaymentsForm
 */
class PaymentsFormTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PaymentsForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PaymentsForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        $this->assertTrue($reflection->hasConstant('GET'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('description'));
        $this->assertTrue($reflection->hasProperty('active'));
    }
    
    /**
     * Тестирует метод PaymentsForm::scenarios
     */
    public function testScenarios()
    {
        $form = new PaymentsForm(['scenario'=>PaymentsForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $this->assertSame(2, $form->id);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'description'=>'Description',
            'active'=>1,
        ];
        
        $this->assertSame('Name', $form->name);
        $this->assertSame('Description', $form->description);
        $this->assertSame(1, $form->active);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::GET]);
        $form->attributes = [
            'id'=>21,
        ];
        
        $this->assertSame(21, $form->id);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::EDIT]);
        $form->attributes = [
            'id'=>5,
            'name'=>'Name',
            'description'=>'Description',
            'active'=>1,
        ];
        
        $this->assertSame(5, $form->id);
        $this->assertSame('Name', $form->name);
        $this->assertSame('Description', $form->description);
        $this->assertSame(1, $form->active);
    }
    
    /**
     * Тестирует метод PaymentsForm::rules
     */
    public function testRules()
    {
        $form = new PaymentsForm(['scenario'=>PaymentsForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::CREATE]);
        $form->validate();
        
        $this->assertCount(2, $form->errors);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::CREATE]);
        $form->attributes = [
            'name'=>self::$_dbClass->payments['delivery_1']['name'],
            'description'=>'Description',
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'description'=>'Description',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::GET]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::GET]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::EDIT]);
        $form->validate();
        
        $this->assertCount(3, $form->errors);
        
        $form = new PaymentsForm(['scenario'=>PaymentsForm::EDIT]);
        $form->attributes = [
            'id'=>45,
            'name'=>'Name',
            'description'=>'Description',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
