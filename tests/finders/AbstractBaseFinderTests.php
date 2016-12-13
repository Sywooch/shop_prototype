<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AbstractBaseFinder;

/**
 * Тестирует класс AbstractBaseFinder
 */
class AbstractBaseFinderTests extends TestCase
{
    private $finder;
    
    public function setUp()
    {
        $this->finder = new class() extends AbstractBaseFinder {
            public $one;
            public $two;
            public function find() {}
            public function rules()
            {
                return [
                    [['one', 'two'], 'safe']
                ];
            }
        };
    }
    
    /**
     * Тестирует метод AbstractBaseFinder::load
     */
    public function testLoad()
    {
        $this->finder->load(['one'=>'ONE', 'two'=>'TWO']);
        
        $reflection = new \ReflectionProperty($this->finder, 'one');
        $result = $reflection->getValue($this->finder);
        
        $this->assertSame('ONE', $result);
        
        $reflection = new \ReflectionProperty($this->finder, 'two');
        $result = $reflection->getValue($this->finder);
        
        $this->assertSame('TWO', $result); 
    }
}
