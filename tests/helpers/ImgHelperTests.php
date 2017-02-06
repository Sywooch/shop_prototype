<?php

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\helpers\ImgHelper;

/**
 * Тестирует класс ImgHelper
 */
class ImgHelperTests extends TestCase
{
    /**
     * Тестирует метод ImgHelper::randThumbn
     */
    public function testRandThumbn()
    {
        $result = ImgHelper::randThumbn('test');
        
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
    }
    
    /**
     * Тестирует метод ImgHelper::allThumbn
     */
    public function testAllThumbn()
    {
        $result = ImgHelper::allThumbn('test');
        
        $this->assertRegExp('#<img src=".+" height="50" alt=""><img src=".+" height="50" alt="">#', $result);
    }
}
