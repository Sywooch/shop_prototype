<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CurrencyWidget;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\services\ServiceInterface;
use app\models\CurrencyModel;
use app\controllers\ProductsListController;
use app\forms\ChangeCurrencyForm;

/**
 * Тестирует класс CurrencyWidget
 */
class CurrencyWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyWidget::class);
        
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод CurrencyWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $model = new class() {};
        
        $widget = new CurrencyWidget();
        $widget->setCurrency($model);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $widget = new CurrencyWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new CurrencyWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends ChangeCurrencyForm {};
        
        $widget = new CurrencyWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new CurrencyWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new CurrencyWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new CurrencyWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new CurrencyWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * если пуст CurrencyWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $widget = new CurrencyWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * если пуст CurrencyWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyService()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * если пуст CurrencyWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $form = new class() extends ChangeCurrencyForm {};
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * если пуст CurrencyWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $form = new class() extends ChangeCurrencyForm {};
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $form = new class() extends ChangeCurrencyForm {};
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'currency-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="set-currency-form"#', $result);
        $this->assertRegExp('#<option value="1">ONE</option>#', $result);
        $this->assertRegExp('#<option value="2">TWO</option>#', $result);
        $this->assertRegExp('#<input type="hidden"#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
    }
}
