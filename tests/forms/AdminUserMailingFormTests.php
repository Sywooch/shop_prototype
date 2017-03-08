<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AdminUserMailingForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    MailingsFixture};

/**
 * Тестирует класс AdminUserMailingForm
 */
class AdminAdminUserMailingFormTests extends TestCase
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
     * Тестирует свойства AdminUserMailingForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserMailingForm::class);
        
        $this->assertTrue($reflection->hasConstant('UNSUBSCRIBE_ACC'));
        $this->assertTrue($reflection->hasConstant('SAVE_ACC'));
        $this->assertTrue($reflection->hasConstant('UNSUBSCRIBE_ADMIN'));
        $this->assertTrue($reflection->hasConstant('SAVE_ADMIN'));
        
        $this->assertTrue($reflection->hasProperty('id_user'));
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('key'));
    }
    
    /**
     * Тестирует метод AdminUserMailingForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::UNSUBSCRIBE_ACC]);
        $form->attributes = [
            'id'=>1,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals(1, $result);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::SAVE_ACC]);
        $form->attributes = [
            'id'=>1,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals(1, $result);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::UNSUBSCRIBE_ADMIN]);
        $form->attributes = [
            'id_user'=>3,
            'id'=>1,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'id_user');
        $result = $reflection->getValue($form);
        $this->assertEquals(3, $result);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::SAVE_ADMIN]);
        $form->attributes = [
            'id_user'=>3,
            'id'=>1,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'id_user');
        $result = $reflection->getValue($form);
        $this->assertEquals(3, $result);
    }
    
    /**
     * Тестирует метод AdminUserMailingForm::rules
     */
    public function testRules()
    {
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::UNSUBSCRIBE_ACC]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::UNSUBSCRIBE_ACC]);
        $form->attributes = [
            'id'=>1,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::SAVE_ACC]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::SAVE_ACC]);
        $form->attributes = [
            'id'=>2,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::UNSUBSCRIBE_ADMIN]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(2, $form->errors);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::UNSUBSCRIBE_ADMIN]);
        $form->attributes = [
            'id_user'=>3,
            'id'=>2,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::SAVE_ADMIN]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(2, $form->errors);
        
        $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::SAVE_ADMIN]);
        $form->attributes = [
            'id_user'=>3,
            'id'=>1,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
