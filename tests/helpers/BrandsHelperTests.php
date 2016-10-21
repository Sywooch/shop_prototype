<?php

namespace app\tests\helpers;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\helpers\BrandsHelper;

/**
 * Тестирует класс app\helpers\BrandsHelper
 */
class BrandsHelperTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>'app\tests\sources\fixtures\BrandsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод BrandsHelper::allMap
     */
    public function testAllMap()
    {
        $fixture1 = self::$_dbClass->brands['brand_1'];
        $fixture2 = self::$_dbClass->brands['brand_2'];
        
        $result = BrandsHelper::allMap('id', 'brand');
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(array_key_exists($fixture1['id'], $result));
        $this->assertTrue(array_key_exists($fixture2['id'], $result));
        $this->assertTrue(in_array($fixture1['brand'], $result));
        $this->assertTrue(in_array($fixture2['brand'], $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
