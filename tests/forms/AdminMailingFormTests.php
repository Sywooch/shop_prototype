<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AdminMailingForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;

/**
 * Тестирует класс AdminMailingForm
 */
class AdminMailingFormTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminMailingForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminMailingForm::class);
        
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
     * Тестирует метод AdminMailingForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $this->assertSame(2, $form->id);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'description'=>'Description',
            'active'=>1,
        ];
        
        $this->assertSame('Name', $form->name);
        $this->assertSame('Description', $form->description);
        $this->assertSame(1, $form->active);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::GET]);
        $form->attributes = [
            'id'=>21,
        ];
        
        $this->assertSame(21, $form->id);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::EDIT]);
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
     * Тестирует метод AdminMailingForm::rules
     */
    public function testRules()
    {
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::CREATE]);
        $form->validate();
        
        $this->assertCount(2, $form->errors);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::CREATE]);
        $form->attributes = [
            'name'=>self::$_dbClass->mailings['delivery_1']['name'],
            'description'=>'Description',
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'description'=>'Description',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::GET]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::GET]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::EDIT]);
        $form->validate();
        
        $this->assertCount(3, $form->errors);
        
        $form = new AdminMailingForm(['scenario'=>AdminMailingForm::EDIT]);
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
