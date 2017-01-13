<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\CustomerInfoForm;

/**
 * Тестирует класс CustomerInfoForm
 */
class CustomerInfoFormTests extends TestCase
{
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
        $this->assertTrue($reflection->hasProperty('delivery'));
        $this->assertTrue($reflection->hasProperty('payment'));
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
            'delivery'=>1,
            'payment'=>1,
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
        
        $reflection = new \ReflectionProperty($form, 'delivery');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'payment');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
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
        $this->assertArrayHasKey('delivery', $form->errors);
        $this->assertArrayHasKey('payment', $form->errors);
        
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
            'delivery'=>1,
            'payment'=>1,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
