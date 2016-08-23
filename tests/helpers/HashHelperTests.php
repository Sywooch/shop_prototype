<?php

namespace app\tests\helpers;

use app\helpers\HashHelper;

/**
 * Тестирует класс app\helpers\HashHelper
 */
class HashHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_a = 'cool';
    private static $_b = '567D7';
    private static $_c = 'Some text';
    
    /**
     * Тестирует метод HashHelper::createHash
     */
    public function testCreateHash()
    {
        $result = HashHelper::createHash([self::$_a, self::$_b, self::$_c]);
        
        $this->assertFalse(empty($result));
        $this->assertEquals(md5(implode('-', [self::$_a, self::$_b, self::$_c])), $result);
    }
}
