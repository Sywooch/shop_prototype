<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\PurchaseForm;

/**
 * Тестирует класс PurchaseForm
 */
class PurchaseFormTests extends TestCase
{
    /**
     * Тестирует свойства PurchaseForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchaseForm::class);
        
        $this->assertTrue($reflection->hasConstant('ADD'));
        
        $this->assertTrue($reflection->hasProperty('quantity'));
        $this->assertTrue($reflection->hasProperty('id_color'));
        $this->assertTrue($reflection->hasProperty('id_size'));
        $this->assertTrue($reflection->hasProperty('id_product'));
        $this->assertTrue($reflection->hasProperty('price'));
    }
}
