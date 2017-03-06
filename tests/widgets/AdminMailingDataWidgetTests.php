<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminMailingDataWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminMailingDataWidget
 */
class AdminMailingDataWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminMailingDataWidget();
    }
    
    /**
     * Тестирует свойства AdminMailingDataWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminMailingDataWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailing'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminMailingDataWidget::setMailing
     */
    public function testSetMailing()
    {
        $mailing = new class() extends Model {};
        
        $this->widget->setMailing($mailing);
        
        $reflection = new \ReflectionProperty($this->widget, 'mailing');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод AdminMailingDataWidget::setForm
     */
    public function testSetForm()
    {
        $mailingsForm = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($mailingsForm);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminMailingDataWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $this->widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminMailingDataWidget::run
     * если пуст AdminMailingDataWidget::mailing
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailing
     */
    public function testRunEmptyMailing()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminMailingDataWidget::run
     * если пуст AdminMailingDataWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'mailing');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminMailingDataWidget::run
     * если пуст AdminMailingDataWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'mailing');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminMailingDataWidget::run
     */
    public function testRun()
    {
        $mailing = new class() {
            public $id = 1;
            public $name = 'Name 1';
            public $description = 'Description 1';
            public $active = 1;
        };
        
        $form = new class() extends AbstractBaseForm{
            public $id;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'mailing');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mailing);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-mailing-data.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#Имя: Name [0-9]{1}#', $result);
        $this->assertRegExp('#Описание: Description [0-9]{1}#', $result);
        $this->assertRegExp('#Активен: .+#', $result);
        $this->assertRegExp('#<form id="admin-mailing-get-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
        $this->assertRegExp('#<form id="admin-mailing-delete-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Удалить">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
