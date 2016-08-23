<?php

namespace app\tests\helpers;

use app\helpers\GeneratorHelper;
use app\tests\MockModel;

/**
 * Тестирует класс app\helpers\GeneratorHelper
 */
class GeneratorHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_array;
    private static $_id = 1;
    
    public static function setUpBeforeClass()
    {
        self::$_array = [
            new MockModel(['id'=>self::$_id]),
            new MockModel(['id'=>self::$_id]),
            new MockModel(['id'=>self::$_id]),
            new MockModel(['id'=>self::$_id]),
            new MockModel(['id'=>self::$_id]),
            new MockModel(['id'=>self::$_id]),
        ];
    }
    
    /**
     * Тестирует метод GeneratorHelper::generate
     */
    public function testGenerate()
    {
        foreach (GeneratorHelper::generate(self::$_array) as $key=>$value) {
            $this->assertTrue(is_object($value));
        }
    }
}
