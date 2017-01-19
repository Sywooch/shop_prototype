<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\MailingForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    MailingsFixture};

/**
 * Тестирует класс MailingForm
 */
class MailingFormTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'emails_mailings'=>EmailsMailingsFixture::class,
                'mailings'=>MailingsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства MailingForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('UNSUBSCRIBE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод MailingForm::scenarios
     */
    public function testScenarios()
    {
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>1,
            'email'=>'some@some.com'
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertEquals('some@some.com', $result);
        
        $form = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE]);
        $form->attributes = [
            'id'=>1,
            'email'=>'some@some.com'
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertEquals('some@some.com', $result);
    }
    
    /**
     * Тестирует метод MailingForm::rules
     */
    public function testRules()
    {
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>'some@some'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>'some@some.com'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>self::$_dbClass->emails['email_1']['email'],
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>[1, 3],
            'email'=>self::$_dbClass->emails['email_1']['email'],
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>[3],
            'email'=>self::$_dbClass->emails['email_1']['email'],
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>'some@some'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>'some@some.com'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
