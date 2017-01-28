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
        
        $this->assertTrue($reflection->hasConstant('GET'));
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
        $this->assertTrue($reflection->hasProperty('id_color'));
        $this->assertTrue($reflection->hasProperty('id_size'));
        $this->assertTrue($reflection->hasProperty('id_delivery'));
        $this->assertTrue($reflection->hasProperty('id_payment'));
        $this->assertTrue($reflection->hasProperty('status'));
    }
    
    /**
     * Тестирует метод AdminChangeOrderForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
        $form->attributes = [
           'id'=>1,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $this->assertSame(1, $reflection->getValue($form));
        
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
           'id_color'=>'id_color',
           'id_size'=>45,
           'id_delivery'=>'id_delivery',
           'id_payment'=>'id_payment',
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
        $reflection = new \ReflectionProperty($form, 'id_color');
        $this->assertSame('id_color', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'id_size');
        $this->assertSame(45, $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'id_delivery');
        $this->assertSame('id_delivery', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'id_payment');
        $this->assertSame('id_payment', $reflection->getValue($form));
        $reflection = new \ReflectionProperty($form, 'status');
        $this->assertSame('status', $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод AdminChangeOrderForm::rules
     */
    public function testRules()
    {
        $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
        $form->attributes = [
            'id'=>1,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
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
           'id_color'=>'id_color',
           'id_size'=>45,
           'id_delivery'=>'id_delivery',
           'id_payment'=>'id_payment',
           'status'=>'status',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
