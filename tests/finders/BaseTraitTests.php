<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BaseTrait;
use yii\base\Model;

/**
 * Тестирует класс BaseTrait
 */
class BaseTraitTests extends TestCase
{
    private $trait;
    
    public function setUp()
    {
        $this->trait = new class() extends Model {
            use BaseTrait;
            public $one;
            public $two;
            public function rules()
            {
                return [
                    [['one', 'two'], 'safe']
                ];
            }
        };
    }
    
    /**
     * Тестирует метод BaseTrait::load
     */
    public function testLoad()
    {
        $this->trait->load(['one'=>'ONE', 'two'=>'TWO']);
        
        $reflection = new \ReflectionProperty($this->trait, 'one');
        $result = $reflection->getValue($this->trait);
        
        $this->assertSame('ONE', $result);
        
        $reflection = new \ReflectionProperty($this->trait, 'two');
        $result = $reflection->getValue($this->trait);
        
        $this->assertSame('TWO', $result); 
    }
}
