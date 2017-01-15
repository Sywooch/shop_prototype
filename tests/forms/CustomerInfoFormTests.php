<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\CustomerInfoForm;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};

/**
 * Тестирует класс CustomerInfoForm
 */
class CustomerInfoFormTests extends TestCase
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
     * Тестирует свойства CustomerInfoForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CustomerInfoForm::class);
        
        $this->assertTrue($reflection->hasConstant('CHECKOUT'));
        
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('surname'));
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('phone'));
        $this->assertTrue($reflection->hasProperty('address'));
        $this->assertTrue($reflection->hasProperty('city'));
        $this->assertTrue($reflection->hasProperty('country'));
        $this->assertTrue($reflection->hasProperty('postcode'));
        $this->assertTrue($reflection->hasProperty('id_delivery'));
        $this->assertTrue($reflection->hasProperty('id_payment'));
        $this->assertTrue($reflection->hasProperty('create'));
        $this->assertTrue($reflection->hasProperty('password'));
        $this->assertTrue($reflection->hasProperty('password2'));
    }
    
    /**
     * Тестирует метод CustomerInfoForm::scenarios
     */
    public function testScenarios()
    {
        $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
        $form->attributes = [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'id_delivery'=>1,
            'id_payment'=>1,
            'create'=>true,
            'password'=>'pass',
            'password2'=>'pass',
        ];
        
        $reflection = new \ReflectionProperty($form, 'name');
        $result = $reflection->getValue($form);
        $this->assertSame('John', $result);
        
        $reflection = new \ReflectionProperty($form, 'surname');
        $result = $reflection->getValue($form);
        $this->assertSame('Doe', $result);
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertSame('jahn@com.com', $result);
        
        $reflection = new \ReflectionProperty($form, 'phone');
        $result = $reflection->getValue($form);
        $this->assertSame('+387968965', $result);
        
        $reflection = new \ReflectionProperty($form, 'address');
        $result = $reflection->getValue($form);
        $this->assertSame('ул. Черноозерная, 1', $result);
        
        $reflection = new \ReflectionProperty($form, 'city');
        $result = $reflection->getValue($form);
        $this->assertSame('Каркоза', $result);
        
        $reflection = new \ReflectionProperty($form, 'country');
        $result = $reflection->getValue($form);
        $this->assertSame('Гиады', $result);
        
        $reflection = new \ReflectionProperty($form, 'postcode');
        $result = $reflection->getValue($form);
        $this->assertSame('08789', $result);
        
        $reflection = new \ReflectionProperty($form, 'id_delivery');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'id_payment');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'create');
        $result = $reflection->getValue($form);
        $this->assertSame(true, $result);
        
        $reflection = new \ReflectionProperty($form, 'password');
        $result = $reflection->getValue($form);
        $this->assertSame('pass', $result);
        
        $reflection = new \ReflectionProperty($form, 'password2');
        $result = $reflection->getValue($form);
        $this->assertSame('pass', $result);
    }
    
    /**
     * Тестирует метод CustomerInfoForm::rules
     */
    public function testRules()
    {
        $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(10, $form->errors);
        $this->assertArrayHasKey('name', $form->errors);
        $this->assertArrayHasKey('surname', $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        $this->assertArrayHasKey('phone', $form->errors);
        $this->assertArrayHasKey('address', $form->errors);
        $this->assertArrayHasKey('city', $form->errors);
        $this->assertArrayHasKey('country', $form->errors);
        $this->assertArrayHasKey('postcode', $form->errors);
        $this->assertArrayHasKey('id_delivery', $form->errors);
        $this->assertArrayHasKey('id_payment', $form->errors);
        
        $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
        $form->attributes = [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'id_delivery'=>1,
            'id_payment'=>1,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
        $form->attributes = [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'id_delivery'=>1,
            'id_payment'=>1,
            'create'=>true,
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        $this->assertArrayHasKey('password2', $form->errors);
        
        $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
        $form->attributes = [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'id_delivery'=>1,
            'id_payment'=>1,
            'create'=>true,
            'password'=>'pass',
            'password2'=>'pass2',
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('password2', $form->errors);
        
        $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
        $form->attributes = [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>self::$dbClass->emails['email_1']['email'],
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'id_delivery'=>1,
            'id_payment'=>1,
            'create'=>true,
            'password'=>'pass',
            'password2'=>'pass',
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
        $form->attributes = [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'id_delivery'=>1,
            'id_payment'=>1,
            'create'=>true,
            'password'=>'pass',
            'password2'=>'pass',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
