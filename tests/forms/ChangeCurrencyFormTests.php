<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\ChangeCurrencyForm;
use app\models\CurrencyModel;

/**
 * Тестирует класс ChangeCurrencyForm
 */
class ChangeCurrencyFormTests extends TestCase
{
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(ChangeCurrencyForm::class);
        
        $this->assertTrue($reflection->hasConstant('CHANGE_CURRENCY'));
        $this->assertTrue($reflection->hasProperty('id'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::CHANGE_CURRENCY]);
        $form->attributes = ['id'=>1];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::CHANGE_CURRENCY]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        $this->assertSame('Необходимо заполнить «Id».', $form->errors['id'][0]);
    }
    
    /**
     * Тестирует метод  ChangeCurrencyForm::getModel
     * при условии, что свойства пусты
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: toArray
     */
    public function testGetModelEmptyProperties()
    {
        $form = new ChangeCurrencyForm();
        $form->getModel('SomeClass');
    }
    
    /**
     * Тестирует метод  ChangeCurrencyForm::getModel
     */
    public function testGetModel()
    {
        $form = new ChangeCurrencyForm();
        
        $reflection = new \ReflectionProperty($form, 'id');
        $reflection->setValue($form, 1);
        
        $result = $form->getModel(CurrencyModel::class);
        
        $this->assertInternalType('object', $result);
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
}
