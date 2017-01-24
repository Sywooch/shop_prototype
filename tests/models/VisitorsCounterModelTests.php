<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\VisitorsCounterModel;

/**
 * Тестирует класс VisitorsCounterModel
 */
class VisitorsCounterModelTests extends TestCase
{
    /**
     * Тестирует свойства VisitorsCounterModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(VisitorsCounterModel::class);
        
        $model = new VisitorsCounterModel();
        
        $this->assertArrayHasKey('date', $model->attributes);
        $this->assertArrayHasKey('counter', $model->attributes);
    }
    
    /**
     * Тестирует метод VisitorsCounterModel::scenarios
     */
    public function testScenarios()
    {
        $model = new VisitorsCounterModel(['scenario'=>VisitorsCounterModel::SAVE]);
        $model->attributes = [
            'date'=>1032654875,
            'counter'=>234
        ];
        
        $this->assertEquals(1032654875, $model->date);
        $this->assertEquals(234, $model->counter);
    }
    
     /**
     * Тестирует метод VisitorsCounterModel::rules
     */
    public function testRules()
    {
        $model = new VisitorsCounterModel(['scenario'=>VisitorsCounterModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(2, $model->errors);
        $this->assertArrayHasKey('date', $model->errors);
        $this->assertArrayHasKey('counter', $model->errors);
        
        $model = new VisitorsCounterModel(['scenario'=>VisitorsCounterModel::SAVE]);
        $model->attributes = [
            'date'=>1032654875,
            'counter'=>234
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
