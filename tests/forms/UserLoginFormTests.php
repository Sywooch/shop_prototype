<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\UserLoginForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};

/**
 * Тестирует класс UserLoginForm
 */
class UserLoginFormTests extends TestCase
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
     * Тестирует свойства UserLoginForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserLoginForm::class);
        
        $this->assertTrue($reflection->hasConstant('LOGIN'));
        $this->assertTrue($reflection->hasConstant('LOGOUT'));
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('password'));
        $this->assertTrue($reflection->hasProperty('id'));
    }
    
    /**
     * Тестирует метод UserLoginForm::scenarios
     */
    public function testScenarios()
    {
        $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
        $form->attributes = [
            'email'=>'some@some.com',
            'password'=>'password'
        ];
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertSame('some@some.com', $result);
        
        $reflection = new \ReflectionProperty($form, 'password');
        $result = $reflection->getValue($form);
        $this->assertSame('password', $result);
        
        $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGOUT]);
        $form->attributes = [
            'id'=>18
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(18, $result);
    }
    
    /**
     * Тестирует метод UserLoginForm::rules
     */
    public function testRules()
    {
        $fixtureEmail = self::$_dbClass->emails['email_1'];
        
        $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        
        $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
        $form->attributes = [
            'email'=>'some',
            'password'=>'password'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
        $form->attributes = [
            'email'=>$fixtureEmail['email'],
            'password'=>'password'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        
        $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGOUT]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        
        $form = new UserLoginForm(['scenario'=>UserLoginForm::LOGOUT]);
        $form->attributes = [
            'id'=>18
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
