<?php

namespace app\tests\helpers;

use app\helpers\AddGetParamsHelper;

/**
 * Тестирует класс app\helpers\AddGetParamsHelper
 */
class AddGetParamsHelperTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тетсирует метод AddGetParamsHelper::addParam
     */
    public function testAddParam()
    {
        $result = AddGetParamsHelper::addParam('http::/someurl.com', ['orderby'=>'surname']);
        $this->assertEquals('http::/someurl.com?orderby=surname', $result);
        
        $result = AddGetParamsHelper::addParam('http::/someurl.com', ['orderby'=>'surname', 'ordertype'=>'desc']);
        $this->assertEquals('http::/someurl.com?orderby=surname&ordertype=desc', $result);
        
        $result = AddGetParamsHelper::addParam('http::/someurl.com?color=black', ['orderby'=>'surname', 'ordertype'=>'desc']);
        $this->assertEquals('http::/someurl.com?color=black&orderby=surname&ordertype=desc', $result);
        
        $result = AddGetParamsHelper::addParam('http::/someurl.com?color=black&orderby=surname&ordertype=desc', ['color'=>'white']);
        $this->assertEquals('http::/someurl.com?color=white&orderby=surname&ordertype=desc', $result);
        
        $result = AddGetParamsHelper::addParam('http::/someurl.com?color=black&orderby=surname&ordertype=desc', ['color'=>'red', 'size'=>45]);
        $this->assertEquals('http::/someurl.com?color=red&orderby=surname&ordertype=desc&size=45', $result);
        
        $result = AddGetParamsHelper::addParam('http::/someurl.com?color=black&orderby=surname&ordertype=desc', ['color'=>'red', 'orderby'=>'firstname', 'size'=>45]);
        $this->assertEquals('http::/someurl.com?color=red&orderby=firstname&ordertype=desc&size=45', $result);
    }
    
    /**
     * Тетсирует метод AddGetParamsHelper::delParam
     */
    public function testDelParam()
    {
        $result = AddGetParamsHelper::delParam('http::/someurl.com?color=black&orderby=surname&ordertype=desc', ['orderby']);
        $this->assertEquals('http::/someurl.com?color=black&ordertype=desc', $result);
        
        $result = AddGetParamsHelper::delParam('http::/someurl.com?color=black&orderby=surname&ordertype=desc', ['color']);
        $this->assertEquals('http::/someurl.com?orderby=surname&ordertype=desc', $result);
        
        $result = AddGetParamsHelper::delParam('http::/someurl.com?color=black&orderby=surname&ordertype=desc', ['orderby', 'color']);
        $this->assertEquals('http::/someurl.com?ordertype=desc', $result);
        
        $result = AddGetParamsHelper::delParam('http::/someurl.com?ordertype=desc', ['ordertype']);
        $this->assertEquals('http::/someurl.com', $result);
    }
}
