<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersSetService;

/**
 * Тестирует класс FiltersSetService
 */
class FiltersSetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersSetService::handle
     */
    public function testHandle()
    {
        $request = [
            'FiltersForm'=>[
                'sortingField'=>'price', 
                'sortingType'=>'SORT_DESC', 
                'colors'=>[2, 5], 
                'sizes'=>[11, 2], 
                'brands'=>[1],
                'url'=>'/shop-12'
            ]
        ];
        
        $service = new FiltersSetService();
        $result = $service->handle($request);
        
        print_r($result);
    }
}
