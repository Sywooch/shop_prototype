<?php

namespace app\tests\helpers;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\helpers\SizesHelper;

/**
 * Тестирует класс app\helpers\SizesHelper
 */
class SizesHelperTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>'app\tests\sources\fixtures\SizesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод SizesHelper::allMap
     */
    public function testAllMap()
    {
        $fixture1 = self::$_dbClass->sizes['size_1'];
        $fixture2 = self::$_dbClass->sizes['size_2'];
        
        $result = SizesHelper::allMap('id', 'size');
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertTrue(array_key_exists($fixture1['id'], $result));
        $this->assertTrue(array_key_exists($fixture2['id'], $result));
        $this->assertTrue(in_array($fixture1['size'], $result));
        $this->assertTrue(in_array($fixture2['size'], $result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
