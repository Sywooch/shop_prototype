<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BaseTrait;
use yii\base\Model;
use yii\db\Query;
use app\filters\ProductsFiltersInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Тестирует класс BaseTrait
 */
class BaseTraitTests extends TestCase
{
    private $trait;
    
    public function setUp()
    {
        $this->trait = new class() extends Model {
            use BaseTrait, ExceptionsTrait;
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
    
    /**
     * Тестирует метод BaseTrait::setFilters
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filter = new class() {};
        
        $this->trait->setFilters($filter);
    }
    
    /**
     * Тестирует метод BaseTrait::setFilters
     */
    public function testSetFilters()
    {
        $filter = new class() implements ProductsFiltersInterface {
            public function getSortingField() {}
            public function getSortingType() {}
            public function getColors() {}
            public function getSizes() {}
            public function getBrands() {}
            public function getUrl() {}
        };
        
        $this->trait->setFilters($filter);
        
        $reflection = new \ReflectionProperty($this->trait, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->trait);
        
        $this->assertInstanceOf(ProductsFiltersInterface::class, $result);
    }
}
