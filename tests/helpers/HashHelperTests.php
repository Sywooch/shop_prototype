<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\HashHelper;
use app\controllers\ProductsListController;
use yii\helpers\Url;

/**
 * Тестирует класс HashHelper
 */
class HashHelperTests extends TestCase
{
     /**
     * Тестирует метод HashHelper::createHash
     * если пуст входящий массив
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: inputArray
     */
    public function testCreateHashEmpty()
    {
        $hash = HashHelper::createHash([]);
    }
    
    /**
     * Тестирует метод HashHelper::createHash
     */
    public function testCreateHash()
    {
        $hash = HashHelper::createHash(['some', 23, 0.234]);
        $this->assertEquals(40, mb_strlen($hash, 'UTF-8'));
        
        $expectedHash = HashHelper::createHash(['some', 23, 0.234]);
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::createFiltersKey
     */
    public function testCreateFiltersKey()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $hash = HashHelper::createFiltersKey(Url::current());
        $this->assertEquals(40, mb_strlen($hash, 'UTF-8'));
        
        $expectedHash = HashHelper::createFiltersKey(Url::current());
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::createCurrencyKey
     */
    public function testCreateCurrencyKey()
    {
        $hash = HashHelper::createCurrencyKey();
        $this->assertEquals(40, mb_strlen($hash, 'UTF-8'));
        
        $expectedHash = HashHelper::createCurrencyKey();
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::createCartKey
     */
    public function testCreateCartKey()
    {
        $hash = HashHelper::createCartKey();
        $this->assertEquals(40, mb_strlen($hash, 'UTF-8'));
        
        $expectedHash = HashHelper::createCartKey();
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::createCartCustomerKey
     */
    public function testCreateCartCustomerKey()
    {
        $hash = HashHelper::createCartCustomerKey();
        $this->assertEquals(40, mb_strlen($hash, 'UTF-8'));
        
        $expectedHash = HashHelper::createCartCustomerKey();
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::createSessionIpKey
     */
    public function testCreateSessionIpKey()
    {
        $hash = HashHelper::createSessionIpKey();
        $this->assertEquals(40, mb_strlen($hash, 'UTF-8'));
        
        $expectedHash = HashHelper::createSessionIpKey();
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::randomString
     */
    public function testRandomString()
    {
        $string = HashHelper::randomString();
        $this->assertEquals(10, mb_strlen($string, 'UTF-8'));
        
        $expectedPass = HashHelper::randomString();
        $this->assertNotEquals($expectedPass, $string);
        
        $string = HashHelper::randomString(20);
        $this->assertEquals(20, mb_strlen($string, 'UTF-8'));
        
        $string = HashHelper::randomString(50);
        $this->assertEquals(40, mb_strlen($string, 'UTF-8'));
    }
}
