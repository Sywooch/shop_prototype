<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\HashHelper;

/**
 * Тестирует класс app\helpers\HashHelper
 */
class HashHelperTests extends TestCase
{
    private static $_elm1 = 'some@some.com';
    private static $_elm2 = 56;
    private static $_elm3 = 14;
    
    /**
     * Тестирует метод HashHelper::createHash
     */
    public function testCreateHash()
    {
        $hash = HashHelper::createHash([self::$_elm1, self::$_elm2, self::$_elm2]);
        
        $this->assertEquals(40, strlen($hash));
        
        $expectedHash = HashHelper::createHash([self::$_elm1, self::$_elm2, self::$_elm2]);
        
        $this->assertEquals($expectedHash, $hash);
    }
}
