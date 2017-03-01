<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminCommentDataWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\models\{CurrencyInterface,
    CurrencyModel};
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCommentDataWidget
 */
class AdminCommentDataWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminCommentDataWidget();
    }
    
    /**
     * Тестирует свойства AdminCommentDataWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCommentDataWidget::class);
        
        $this->assertTrue($reflection->hasProperty('commentsModel'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminCommentDataWidget::setCommentsModel
     */
    public function testSetCommentsModel()
    {
        $commentsModel = new class() extends Model {};
        
        $this->widget->setCommentsModel($commentsModel);
        
        $reflection = new \ReflectionProperty($this->widget, 'commentsModel');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод AdminCommentDataWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($form);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminCommentDataWidget::setTemplate
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
     * Тестирует метод AdminCommentDataWidget::run
     * если пуст AdminCommentDataWidget::commentsModel
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: commentsModel
     */
    public function testRunEmptyCommentsModel()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentDataWidget::run
     * если пуст AdminCommentDataWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'commentsModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentDataWidget::run
     * если пуст AdminCommentDataWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'commentsModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentDataWidget::run
     */
    public function testRun()
    {
        $commentsModel = new class() {
            public $id = 1;
            public $date;
            public $id_name = true;
            public $name;
            public $id_email = true;
            public $email;
            public $id_product = true;
            public $product;
            public $text = 'Text';
            public $active = 1;
            public function __construct()
            {
                $this->date = time();
                $this->name = new class() {
                    public $name = 'Name 1';
                };
                $this->email = new class() {
                    public $email = 'email@email.net';
                };
                $this->product = new class() {
                    public $name = 'name';
                    public $seocode = 'name';
                };
            }
        };
        
        $form = new class() extends AbstractBaseForm {
            public $id;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'commentsModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $commentsModel);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-comment-data.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div class="admin-comment-previous-data">#', $result);
        $this->assertRegExp('#Id комментария: [0-9]{1}#', $result);
        $this->assertRegExp('#Дата добавления: .+#', $result);
        $this->assertRegExp('#Комментатор: .+#', $result);
        $this->assertRegExp('#Email: .+@.+#', $result);
        $this->assertRegExp('#Текст комментария: .+#', $result);
        $this->assertRegExp('#Активен: .+#', $result);
        $this->assertRegExp('#<form id="admin-comment-detail-get-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
        $this->assertRegExp('#<form id="admin-comment-detail-delete-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Удалить">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
