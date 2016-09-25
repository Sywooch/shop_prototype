<?php

namespace app\tests;

use yii\base\Object;

class MockObject extends Object
{
    public $description;
    
    public static function className()
    {
        return 'app\tests\MockObject';
    }
    
    public function getRoute()
    {
    }
}
