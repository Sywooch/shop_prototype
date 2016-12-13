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
        
        $model = new CurrencyModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('code', $model->attributes);
        $this->assertArrayHasKey('exchange_rate', $model->attributes);
        $this->assertArrayHasKey('main', $model->attributes);
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
            'code'=>'RUTG-8',
            'exchange_rate'=>23.17,
            'main'=>true
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertSame(2, $result['id']);
        $this->assertArrayHasKey('code', $result);
        $this->assertSame('RUTG-8', $result['code']);
        $this->assertArrayHasKey('exchange_rate', $result);
        $this->assertSame(23.17, $result['exchange_rate']);
        $this->assertArrayHasKey('main', $result);
        $this->assertSame(true, $result['main']);
    }
}
