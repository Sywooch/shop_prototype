<?php

namespace app\tests\helpers;

use yii\helpers\Url;
use PHPUnit\Framework\TestCase;
use app\helpers\UrlHelper;
use app\helpers\SessionHelper;

/**
 * Тестирует класс app\helpers\UrlHelper
 */
class UrlHelperTests extends TestCase
{
    /**
     * Тестирует метод UrlHelper::previous
     */
    public function testPrevious()
    {
        $result = UrlHelper::previous('test');
        
        $expectedUrl = '../vendor/phpunit/phpunit/catalog';
        
        $this->assertEquals($expectedUrl, $result);
        
        Url::remember('https://shop.com/cart', 'test');
        
        $result = UrlHelper::previous('test');
        
        $expectedUrl = 'https://shop.com/cart';
        
        $this->assertEquals($expectedUrl, $result);
    }
    
    public static function tearDownAfterClass()
    {
        SessionHelper::remove(['test']);
    }
}
