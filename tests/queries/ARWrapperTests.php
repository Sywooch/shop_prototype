<?php

namespace app\tests\queries;

use PHPUnit\Framework\TestCase;
use app\queries\ARWrapper;

/**
 * Тестирует класс app\queries\ARWrapper
 */
class ARWrapperTests extends TestCase
{
    private static $_data = [
        [
            'one'=>1, 
            'two'=>'Root', 
            'three'=>[
                [
                    'one'=>12, 
                    'two'=>'True'
                ], 
                [
                    'one'=>45, 
                    'two'=>'Puls'
                ]
            ],
        ]
    ];
    
    /**
     * Тестирует метод ARWrapper::set
     */
    public function testSet()
    {
        $result = ARWrapper::set(self::$_data);
        
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, $result[0]->one);
        $this->assertEquals('Root', $result[0]->two);
        $this->assertTrue($result[0]->three instanceof ARWrapper);
        
        foreach ($result[0]->three as $data) {
            $this->assertTrue(in_array($data->one, [12, 45]));
            $this->assertTrue(in_array($data->two, ['True', 'Puls']));
        }
    }
}
