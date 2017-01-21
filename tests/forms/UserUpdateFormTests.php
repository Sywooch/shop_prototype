<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\UserUpdateForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;

/**
 * Тестирует класс UserUpdateForm
 */
class UserUpdateFormTests extends TestCase
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
     * Тестирует свойства UserUpdateForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserUpdateForm::class);
        
        $this->assertTrue($reflection->hasConstant('UPDATE'));
        
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('surname'));
        $this->assertTrue($reflection->hasProperty('phone'));
        $this->assertTrue($reflection->hasProperty('address'));
        $this->assertTrue($reflection->hasProperty('city'));
        $this->assertTrue($reflection->hasProperty('country'));
        $this->assertTrue($reflection->hasProperty('postcode'));
    }
    
    /**
     * Тестирует метод UserUpdateForm::scenarios
     */
    public function testScenarios()
    {
        $form = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
        $form->attributes = [
            'name'=>'Name',
            'surname'=>'Surname',
            'phone'=>'+045 897-99-20',
            'address'=>'Address str., 19',
            'city'=>'City',
            'country'=>'Country',
            'postcode'=>'056987',
        ];
        
        $reflection = new \ReflectionProperty($form, 'name');
        $result = $reflection->getValue($form);
        $this->assertSame('Name', $result);
        
        $reflection = new \ReflectionProperty($form, 'surname');
        $result = $reflection->getValue($form);
        $this->assertSame('Surname', $result);
        
        $reflection = new \ReflectionProperty($form, 'phone');
        $result = $reflection->getValue($form);
        $this->assertSame('+045 897-99-20', $result);
        
        $reflection = new \ReflectionProperty($form, 'address');
        $result = $reflection->getValue($form);
        $this->assertSame('Address str., 19', $result);
        
        $reflection = new \ReflectionProperty($form, 'city');
        $result = $reflection->getValue($form);
        $this->assertSame('City', $result);
        
        $reflection = new \ReflectionProperty($form, 'country');
        $result = $reflection->getValue($form);
        $this->assertSame('Country', $result);
        
        $reflection = new \ReflectionProperty($form, 'postcode');
        $result = $reflection->getValue($form);
        $this->assertSame('056987', $result);
    }
    
    /**
     * Тестирует метод UserUpdateForm::rules
     */
    public function testRules()
    {
        $form = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(7, $form->errors);
        $this->assertArrayHasKey('name', $form->errors);
        $this->assertArrayHasKey('surname', $form->errors);
        $this->assertArrayHasKey('phone', $form->errors);
        $this->assertArrayHasKey('address', $form->errors);
        $this->assertArrayHasKey('city', $form->errors);
        $this->assertArrayHasKey('country', $form->errors);
        $this->assertArrayHasKey('postcode', $form->errors);
        
        $form = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
        $form->attributes = [
            'name'=>'Name',
            'surname'=>'Surname',
            'phone'=>'+045 897-99-20',
            'address'=>'Address str., 19',
            'city'=>'City',
            'country'=>'Country',
            'postcode'=>'056987',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
