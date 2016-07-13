<?php

namespace app\tests\helpers;

use app\helpers\RedirectHelper;
use app\tests\MockModel;

/**
 * Тестирует класс app\helpers\RedirectHelper
 */
class RedirectHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_searchData = 'пиджак';
    private static $_categories = 'menswear';
    private static $_subcategory = 'coats';
    
    /**
     * Тестирует метод RedirectHelper::getRedirectUrl()
     * при условии отсутствия в $_GET categories, subcategory, search
     */
    public function testGetRedirectUrlOne()
    {
        $_GET = [];
        
        $model = new MockModel();
        
        $result = RedirectHelper::getRedirectUrl($model);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(1, count($result));
        $this->assertTrue(in_array('products-list/index', $result));
    }
    
    /**
     * Тестирует метод RedirectHelper::getRedirectUrl()
     * при условии наличия в $_GET search и отсутствия в $_GET categories, subcategory
     */
    public function testGetRedirectUrlTwo()
    {
        $_GET = ['search'=>self::$_searchData];
        
        $model = new MockModel(['search'=>self::$_searchData]);
        
        $result = RedirectHelper::getRedirectUrl($model);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(2, count($result));
        $this->assertTrue(in_array('products-list/search', $result));
        $this->assertTrue(array_key_exists('search', $result));
        $this->assertTrue(in_array(self::$_searchData, $result));
    }
    
    /**
     * Тестирует метод RedirectHelper::getRedirectUrl()
     * при условии наличия в $_GET categories и отсутствия в $_GET subcategory, search
     */
    public function testGetRedirectUrlThree()
    {
        $_GET = ['categories'=>self::$_categories];
        
        $model = new MockModel(['categories'=>self::$_categories]);
        
        $result = RedirectHelper::getRedirectUrl($model);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(2, count($result));
        $this->assertTrue(in_array('products-list/index', $result));
        $this->assertTrue(array_key_exists('categories', $result));
        $this->assertTrue(in_array(self::$_categories, $result));
    }
    
    /**
     * Тестирует метод RedirectHelper::getRedirectUrl()
     * при условии наличия в $_GET categories, subcategory и отсутствия в $_GET search
     */
    public function testGetRedirectUrlFour()
    {
        $_GET = ['categories'=>self::$_categories, 'subcategory'=>self::$_subcategory];
        
        $model = new MockModel(['categories'=>self::$_categories, 'subcategory'=>self::$_subcategory]);
        
        $result = RedirectHelper::getRedirectUrl($model);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(3, count($result));
        $this->assertTrue(in_array('products-list/index', $result));
        $this->assertTrue(array_key_exists('categories', $result));
        $this->assertTrue(in_array(self::$_categories, $result));
        $this->assertTrue(array_key_exists('subcategory', $result));
        $this->assertTrue(in_array(self::$_subcategory, $result));
    }
}
