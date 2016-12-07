<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AbstractBaseForm;
use yii\base\Model;

/**
 * Тестирует класс AbstractBaseForm
 */
class AbstractBaseFormTests extends TestCase
{
    private $form;
    private $model;
    
    public function setUp()
    {
        $this->form = new class() extends AbstractBaseForm {
            public $id;
        };
        
        $this->model = new class() extends Model {
            public $id;
        };
    }
    
    /**
     * Тестирует метод  AbstractBaseForm::getModel
     * песли свойства пусты
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: toArray
     */
    public function testGetModelEmptyProperties()
    {
        $this->form->getModel('SomeClass');
    }
    
    /**
     * Тестирует метод  AbstractBaseForm::getModel
     */
    public function testGetModel()
    {
        $reflection = new \ReflectionProperty($this->form, 'id');
        $reflection->setValue($this->form, 75);
        
        $result = $this->form->getModel($this->model::className());
        
        $this->assertInternalType('object', $result);
        $this->assertInstanceOf($this->model::className(), $result);
        $this->assertSame(75, $result->id);
    }
}
