<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\UserMailingForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    MailingsFixture};

/**
 * Тестирует класс UserMailingForm
 */
class UserMailingFormTests extends TestCase
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
     * Тестирует свойства UserMailingForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserMailingForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('UNSUBSCRIBE'));
        
        $this->assertTrue($reflection->hasProperty('id_user'));
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('key'));
    }
    
    /**
     * Тестирует метод UserMailingForm::scenarios
     */
    public function testScenarios()
    {
        $form = new UserMailingForm(['scenario'=>UserMailingForm::SAVE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>'some@some.com'
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals([1], $result);
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertEquals('some@some.com', $result);
        
        $form = new UserMailingForm(['scenario'=>UserMailingForm::UNSUBSCRIBE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>'some@some.com',
            'key'=>'key'
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals([1], $result);
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertEquals('some@some.com', $result);
        
        $reflection = new \ReflectionProperty($form, 'key');
        $result = $reflection->getValue($form);
        $this->assertEquals('key', $result);
    }
    
    /**
     * Тестирует метод UserMailingForm::rules
     */
    public function testRules()
    {
        $form = new UserMailingForm(['scenario'=>UserMailingForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(2, $form->errors);
        
        $form = new UserMailingForm(['scenario'=>UserMailingForm::SAVE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>'some@some'
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new UserMailingForm(['scenario'=>UserMailingForm::SAVE]);
        $form->attributes = [
            'id'=>[1],
            'email'=>'some@some.com'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new UserMailingForm(['scenario'=>UserMailingForm::SAVE]);
        $form->attributes = [
            'id'=>[3],
            'email'=>self::$_dbClass->emails['email_2']['email'],
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new UserMailingForm(['scenario'=>UserMailingForm::SAVE]);
        $form->attributes = [
            'id'=>[3],
            'email'=>self::$_dbClass->emails['email_1']['email'],
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new UserMailingForm(['scenario'=>UserMailingForm::UNSUBSCRIBE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(3, $form->errors);
        
        $form = new UserMailingForm(['scenario'=>UserMailingForm::UNSUBSCRIBE]);
        $form->attributes = [
            'id'=>[2],
            'email'=>'some@some',
            'key'=>sha1('key')
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new UserMailingForm(['scenario'=>UserMailingForm::UNSUBSCRIBE]);
        $form->attributes = [
            'id'=>[2],
            'email'=>'some@some.com',
            'key'=>sha1('key')
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
