<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\DeliveriesForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\DeliveriesFixture;

/**
 * Тестирует класс DeliveriesForm
 */
class DeliveriesFormTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства DeliveriesForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(DeliveriesForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        $this->assertTrue($reflection->hasConstant('GET'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('description'));
        $this->assertTrue($reflection->hasProperty('price'));
        $this->assertTrue($reflection->hasProperty('active'));
    }
    
    /**
     * Тестирует метод DeliveriesForm::scenarios
     */
    public function testScenarios()
    {
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $this->assertSame(2, $form->id);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'description'=>'Description',
            'price'=>35.89,
            'active'=>1,
        ];
        
        $this->assertSame('Name', $form->name);
        $this->assertSame('Description', $form->description);
        $this->assertSame(35.89, $form->price);
        $this->assertSame(1, $form->active);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::GET]);
        $form->attributes = [
            'id'=>21,
        ];
        
        $this->assertSame(21, $form->id);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::EDIT]);
        $form->attributes = [
            'id'=>5,
            'name'=>'Name',
            'description'=>'Description',
            'price'=>35.89,
            'active'=>1,
        ];
        
        $this->assertSame(5, $form->id);
        $this->assertSame('Name', $form->name);
        $this->assertSame('Description', $form->description);
        $this->assertSame(35.89, $form->price);
        $this->assertSame(1, $form->active);
    }
    
    /**
     * Тестирует метод DeliveriesForm::rules
     */
    public function testRules()
    {
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::CREATE]);
        $form->validate();
        
        $this->assertCount(3, $form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::CREATE]);
        $form->attributes = [
            'name'=>self::$_dbClass->deliveries['delivery_1']['name'],
            'description'=>'Description',
            'price'=>35.89,
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'description'=>'Description',
            'price'=>35.89,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::GET]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::GET]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::EDIT]);
        $form->validate();
        
        $this->assertCount(4, $form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::EDIT]);
        $form->attributes = [
            'id'=>45,
            'name'=>'Name',
            'description'=>'Description',
            'price'=>35.89,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
