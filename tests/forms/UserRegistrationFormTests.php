<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\UserRegistrationForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};

/**
 * Тестирует класс UserRegistrationForm
 */
class UserRegistrationFormTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'users'=>UsersFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    
    /**
     * Тестирует свойства UserRegistrationForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRegistrationForm::class);
        
        $this->assertTrue($reflection->hasConstant('REGISTRATION'));
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('password'));
        $this->assertTrue($reflection->hasProperty('password2'));
    }
    
    /**
     * Тестирует метод UserRegistrationForm::scenarios
     */
    public function testScenarios()
    {
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->attributes = [
            'email'=>'some@some.com',
            'password'=>'password',
            'password2'=>'password'
        ];
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertSame('some@some.com', $result);
        
        $reflection = new \ReflectionProperty($form, 'password');
        $result = $reflection->getValue($form);
        $this->assertSame('password', $result);
        
        $reflection = new \ReflectionProperty($form, 'password2');
        $result = $reflection->getValue($form);
        $this->assertSame('password', $result);
    }
    
    /**
     * Тестирует метод UserRegistrationForm::rules
     */
    public function testRules()
    {
        $emailFixture = self::$dbClass->emails['email_1'];
        
        # Если все поля пусты
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(3, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        $this->assertArrayHasKey('password2', $form->errors);
        
        # Если передан некорректный email
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->attributes = [
            'email'=>'some@some',
            'password'=>'password',
            'password2'=>'password'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        # Если передан уже связанный с пользователем email
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->attributes = [
            'email'=>$emailFixture['email'],
            'password'=>'password',
            'password2'=>'password'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        # Если пароли не совпадают
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->attributes = [
            'email'=>'some@some.com',
            'password'=>'password',
            'password2'=>'password2'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('password2', $form->errors);
        
        # Если все поля корректны
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->attributes = [
            'email'=>'some@some.com',
            'password'=>'password',
            'password2'=>'password'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
