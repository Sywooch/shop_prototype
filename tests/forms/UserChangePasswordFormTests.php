<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\UserChangePasswordForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс UserChangePasswordForm
 */
class UserChangePasswordFormTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства UserChangePasswordForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserChangePasswordForm::class);
        
        $this->assertTrue($reflection->hasConstant('CHANGE'));
        
        $this->assertTrue($reflection->hasProperty('currentPassword'));
        $this->assertTrue($reflection->hasProperty('password'));
        $this->assertTrue($reflection->hasProperty('password2'));
    }
    
    /**
     * Тестирует метод UserChangePasswordForm::scenarios
     */
    public function testScenarios()
    {
        $form = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::CHANGE]);
        $form->attributes = [
            'currentPassword'=>'Ui7Htyy',
            'password'=>'9Iui7Yhh',
            'password2'=>'9Iui7Yhh',
        ];
        
        $reflection = new \ReflectionProperty($form, 'currentPassword');
        $result = $reflection->getValue($form);
        $this->assertSame('Ui7Htyy', $result);
        
        $reflection = new \ReflectionProperty($form, 'password');
        $result = $reflection->getValue($form);
        $this->assertSame('9Iui7Yhh', $result);
        
        $reflection = new \ReflectionProperty($form, 'password2');
        $result = $reflection->getValue($form);
        $this->assertSame('9Iui7Yhh', $result);
    }
    
    /**
     * Тестирует метод UserChangePasswordForm::rules
     */
    public function testRules()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $form = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::CHANGE]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(3, $form->errors);
        $this->assertArrayHasKey('currentPassword', $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        $this->assertArrayHasKey('password2', $form->errors);
        
        $form = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::CHANGE]);
        $form->attributes = [
            'currentPassword'=>'Ui7Htyy',
            'password'=>'9Iui7Yhh',
            'password2'=>'9Iui7Yhh',
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('currentPassword', $form->errors);
        
        $form = new UserChangePasswordForm(['scenario'=>UserChangePasswordForm::CHANGE]);
        $form->attributes = [
            'currentPassword'=>'Ui7Htyy',
            'password'=>'9Iui7Yhh',
            'password2'=>'8Iui7Yhh',
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('currentPassword', $form->errors);
        $this->assertArrayHasKey('password2', $form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
