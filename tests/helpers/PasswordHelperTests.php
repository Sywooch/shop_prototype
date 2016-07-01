<?php

namespace app\tests\helpers;

use app\helpers\PasswordHelper;

/**
 * Тестирует app\helpers\PasswordHelper
 */
class PasswordHelperTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод PasswordHelper::getPassword
     */
    public function testGetPassword()
    {
        $result = NULL;
        
        $result = PasswordHelper::getPassword();
        
        $this->assertFalse(empty($result));
        $this->assertEquals(10, strlen($result));
    }
}
