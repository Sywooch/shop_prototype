<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\RecoveryModel;

/**
 * Тестирует класс RecoveryModel
 */
class RecoveryModelTests extends TestCase
{
    /**
     * Тестирует свойства RecoveryModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RecoveryModel::class);
        
        $this->assertTrue($reflection->hasConstant('SET'));
        
        $this->assertTrue($reflection->hasProperty('key'));
    }
    
    /**
     * Тестирует метод RecoveryModel::scenarios
     */
    public function testScenarios()
    {
        $form = new RecoveryModel(['scenario'=>RecoveryModel::SET]);
        $form->attributes = ['key'=>'key'];
        
        $reflection = new \ReflectionProperty($form, 'key');
        $result = $reflection->getValue($form);
        
        $this->assertSame('key', $result);
    }
    
    
    /**
     * Тестирует метод RecoveryModel::rules
     */
    public function testRules()
    {
        $form = new RecoveryModel(['scenario'=>RecoveryModel::SET]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertArrayHasKey('key', $form->errors);
        
        $form = new RecoveryModel(['scenario'=>RecoveryModel::SET]);
        $form->attributes = ['key'=>'key'];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
