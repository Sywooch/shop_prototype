<?php

namespace app\tests\helpers;

use app\helpers\HashHelper;

/**
 * Тестирует класс app\helpers\HashHelper
 */
class HashHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $a = 'cool';
    private static $b = '567D7';
    private static $c = 'Some text';
    
    /**
     * Тестирует метод HashHelper::createHash
     */
    public function testCreateHash()
    {
        $result = HashHelper::createHash([self::$a, self::$b, self::$c]);
        
        $this->assertFalse(empty($result));
        $this->assertEquals(md5(implode('-', [self::$a, self::$b, self::$c])), $result);
    }
}
