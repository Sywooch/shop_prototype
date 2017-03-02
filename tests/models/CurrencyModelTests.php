<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyModel
 */
class CurrencyModelTests extends TestCase
{
    /**
     * Тестирует свойства CurrencyModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyModel::class);
        
        $this->assertTrue($reflection->hasConstant('DBMS'));
        $this->assertTrue($reflection->hasConstant('UPDATE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $model = new CurrencyModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('code', $model->attributes);
        $this->assertArrayHasKey('exchange_rate', $model->attributes);
        $this->assertArrayHasKey('main', $model->attributes);
        $this->assertArrayHasKey('update_date', $model->attributes);
    }
    
    /**
     * Тестирует метод CurrencyModel::tableName
     */
    public function testTableName()
    {
        $result = CurrencyModel::tableName();
        
        $this->assertSame('currency', $result);
    }
    
    /**
     * Тестирует метод CurrencyModel::scenarios
     */
    public function testScenarios()
    {
        $model = new CurrencyModel(['scenario'=>CurrencyModel::DBMS]);
        $model->attributes = [
            'id'=>2,
            'code'=>'USD',
            'exchange_rate'=>23.17,
            'main'=>1,
            'update_date'=>time()
        ];
        
        $this->assertEquals(2, $model->id);
        $this->assertEquals('USD', $model->code);
        $this->assertEquals(23.17, $model->exchange_rate);
        $this->assertEquals(1, $model->main);
        $this->assertEquals(time(), $model->update_date);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::UPDATE]);
        $model->attributes = [
            'id'=>2,
            'exchange_rate'=>1.056,
            'update_date'=>time()
        ];
        
         $this->assertEquals(2, $model->id);
        $this->assertEquals(1.056, $model->exchange_rate);
        $this->assertEquals(time(), $model->update_date);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::CREATE]);
        $model->attributes = [
            'code'=>'USD',
            'exchange_rate'=>1.056,
            'main'=>1,
            'update_date'=>time()
        ];
        
        $this->assertEquals('USD', $model->code);
        $this->assertEquals(1.056, $model->exchange_rate);
        $this->assertEquals(1, $model->main);
        $this->assertEquals(time(), $model->update_date);
    }
    
    /**
     * Тестирует метод CurrencyModel::rules
     */
    public function testRules()
    {
        $model = new CurrencyModel(['scenario'=>CurrencyModel::UPDATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(3, $model->errors);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::UPDATE]);
        $model->attributes = [
            'id'=>2,
            'exchange_rate'=>1.056,
            'update_date'=>time()
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::CREATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(3, $model->errors);
        
        $model = new CurrencyModel(['scenario'=>CurrencyModel::CREATE]);
        $model->attributes = [
            'code'=>'USD',
            'exchange_rate'=>1.056,
            'update_date'=>time()
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->main);
    }
    
    /**
     * Тестирует метод CurrencyModel::exchangeRate
     */
    public function testExchangeRate()
    {
        $model = new CurrencyModel([
            'exchange_rate'=>12.2,
        ]);
        
        $result = $model ->exchangeRate();
        
        $this->assertEquals(12.2, $result);
    }
    
    /**
     * Тестирует метод CurrencyModel::code
     */
    public function testCode()
    {
        $model = new CurrencyModel([
            'code'=>'USD',
        ]);
        
        $result = $model ->code();
        
        $this->assertEquals('USD', $result);
    }
}
