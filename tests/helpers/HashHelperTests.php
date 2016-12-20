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
     */
    public function testCreateHash()
    {
        $hash = HashHelper::createHash(['some', 23, 0.234]);
        
        $this->assertEquals(40, strlen($hash));
        
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
        
        $this->assertEquals(40, strlen($hash));
        
        $expectedHash = HashHelper::createFiltersKey(Url::current());
        
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::createCurrencyKey
     */
    public function testCreateCurrencyKey()
    {
        $hash = HashHelper::createCurrencyKey();
        
        $this->assertEquals(40, strlen($hash));
        
        $expectedHash = HashHelper::createCurrencyKey();
        
        $this->assertEquals($expectedHash, $hash);
    }
    
    /**
     * Тестирует метод HashHelper::createCartKey
     */
    public function testCreateCartKey()
    {
        $hash = HashHelper::createCartKey();
        
        $this->assertEquals(40, strlen($hash));
        
        $expectedHash = HashHelper::createCartKey();
        
        $this->assertEquals($expectedHash, $hash);
    }
}
