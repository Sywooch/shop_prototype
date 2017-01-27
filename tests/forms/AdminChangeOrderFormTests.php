<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AdminChangeOrderForm;

/**
 * Тестирует класс AdminChangeOrderForm
 */
class AdminChangeOrderFormTests extends TestCase
{
    /**
     * Тестирует свойства AdminChangeOrderForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminChangeOrderForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('surname'));
        $this->assertTrue($reflection->hasProperty('phone'));
        $this->assertTrue($reflection->hasProperty('address'));
        $this->assertTrue($reflection->hasProperty('city'));
        $this->assertTrue($reflection->hasProperty('country'));
        $this->assertTrue($reflection->hasProperty('postcode'));
        $this->assertTrue($reflection->hasProperty('quantity'));
        $this->assertTrue($reflection->hasProperty('color'));
        $this->assertTrue($reflection->hasProperty('size'));
        $this->assertTrue($reflection->hasProperty('delivery'));
        $this->assertTrue($reflection->hasProperty('payment'));
        $this->assertTrue($reflection->hasProperty('status'));
    }
    
    /**
     * Тестирует метод AdminChangeOrderForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::SAVE]);
        $form->attributes = [
           'id'=>1,
           'name'=>'Name',
           'surname'=>'Surname',
           'phone'=>'458-01-11',
           'address'=>'Address str, 1',
           'city'=>'City',
           'country'=>'Country',
           'postcode'=>'postcode',
           'quantity'=>2,
           'color'=>'color',
           'size'=>45,
           'delivery'=>'delivery',
           'payment'=>'payment',
           'status'=>'status',
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $this->assertSame(1, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'name');
        $this->assertSame('Name', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'surname');
        $this->assertSame('Surname', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'phone');
        $this->assertSame('458-01-11', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'address');
        $this->assertSame('Address str, 1', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'city');
        $this->assertSame('City', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'country');
        $this->assertSame('Country', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'postcode');
        $this->assertSame('postcode', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'quantity');
        $this->assertSame(2, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'color');
        $this->assertSame('color', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'size');
        $this->assertSame(45, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'delivery');
        $this->assertSame('delivery', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'payment');
        $this->assertSame('payment', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'status');
        $this->assertSame('status', $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод AdminChangeOrderForm::rules
     */
    public function testRules()
    {
        $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(14, $form->errors);
        
        $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::SAVE]);
        $form->attributes = [
           'id'=>1,
           'name'=>'Name',
           'surname'=>'Surname',
           'phone'=>'458-01-11',
           'address'=>'Address str, 1',
           'city'=>'City',
           'country'=>'Country',
           'postcode'=>'postcode',
           'quantity'=>2,
           'color'=>'color',
           'size'=>45,
           'delivery'=>'delivery',
           'payment'=>'payment',
           'status'=>'status',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
