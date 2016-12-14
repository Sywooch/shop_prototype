<?php

namespace app\tests\savers;

use PHPUnit\Framework\TestCase;
use app\savers\AbstractBaseSaver;

/**
 * Тестирует класс AbstractBaseSaver
 */
class AbstractBaseSaverTests extends TestCase
{
    private $finder;
    
    public function setUp()
    {
        $this->finder = new class() extends AbstractBaseSaver {
            public $one;
            public $two;
            public function save() {}
            public function rules()
            {
                return [
                    [['one', 'two'], 'safe']
                ];
            }
        };
    }
    
    /**
     * Тестирует метод AbstractBaseSaver::load
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
