<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\UserIpModel;

/**
 * Тестирует класс UserIpModel
 */
class UserIpModelTests extends TestCase
{
    /**
     * Тестирует свойства UserIpModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserIpModel::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        $this->assertTrue($reflection->hasProperty('ip'));
    }
    
    /**
     * Тестирует метод UserIpModel::scenarios
     */
    public function testScenarios()
    {
        $model = new UserIpModel(['scenario'=>UserIpModel::SESSION]);
        $model->attributes = [
            'ip'=>'127.0.0.1'
        ];
        
        $this->assertEquals('127.0.0.1', $model->ip);
    }
    
    /**
     * Тестирует метод UserIpModel::rules
     */
    public function testRules()
    {
        $model = new UserIpModel(['scenario'=>UserIpModel::SESSION]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new UserIpModel(['scenario'=>UserIpModel::SESSION]);
        $model->attributes = [
            'ip'=>'127.0.0.1'
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
