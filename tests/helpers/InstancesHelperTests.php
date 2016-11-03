<?php

namespace app\tests\helpers;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\helpers\InstancesHelper;
use app\models\{CategoriesModel,
    CurrencyModel};

/**
 * Тестирует класс app\helpers\InstancesHelper
 */
class InstancesHelperTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>'app\tests\sources\fixtures\CategoriesFixture',
                'currency'=>'app\tests\sources\fixtures\CurrencyFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод InstancesHelper::getInstances
     */
    public function testGetInstances()
    {
        $categoryFixture = self::$_dbClass->categories['category_1'];
        $currencyFixture = self::$_dbClass->currency['currency_1'];
        
        $result = InstancesHelper::getInstances();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertTrue(array_key_exists('categoriesList', $result));
        $this->assertTrue(is_array($result['categoriesList']));
        $this->assertFalse(empty($result['categoriesList']));
        foreach ($categoryFixture as $key=>$val) {
            $this->assertFalse(empty($result['categoriesList'][0]->$key));
        }
        
        $this->assertTrue(array_key_exists('currencyList', $result));
        $this->assertTrue(is_array($result['currencyList']));
        $this->assertFalse(empty($result['currencyList']));
        $this->assertTrue(array_key_exists($currencyFixture['id'], $result['currencyList']));
        $this->assertTrue(in_array($currencyFixture['code'], $result['currencyList']));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
