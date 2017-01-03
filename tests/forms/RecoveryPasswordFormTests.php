<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\RecoveryPasswordForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};

/**
 * Тестирует класс RecoveryPasswordForm
 */
class RecoveryPasswordFormTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства RecoveryPasswordForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RecoveryPasswordForm::class);
        
        $this->assertTrue($reflection->hasConstant('GET'));
        
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод RecoveryPasswordForm::scenarios
     */
    public function testScenarios()
    {
        $form = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
        $form->attributes = ['email'=>'email@hub.com'];
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        
        $this->assertSame('email@hub.com', $result);
    }
    
    /**
     * Тестирует метод RecoveryPasswordForm::rules
     */
    public function testRules()
    {
        $fixtureEmail = self::$_dbClass->emails['email_1'];
        
        $form = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
        $form->attributes = ['email'=>'email@hub'];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
        $form->attributes = ['email'=>'email@hub.com'];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
        $form->attributes = ['email'=>$fixtureEmail['email']];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
